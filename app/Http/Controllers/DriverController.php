<?php

namespace App\Http\Controllers;

use App\Events\DriverLocationUpdated;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\College;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{

    public function Allcolleges()
    {
        $user = Auth::user();

        // 🎒 1. STUDENT VIEW LOGIC
        if ($user && $user->hasRole('student')) {
            // We MUST fetch the active ride to see if a driver accepted it!
            $ride = Ride::with('driver')
                ->where('student_id', $user->id)
                ->whereIn('status', ['pending', 'accepted', 'picked_up'])
                ->first();

            // Return the MAIN page, and pass the ride data to it
            return view('components.card-driver', [
                'ride' => $ride
            ]);
        }

        // 🚕 2. DRIVER VIEW LOGIC
        // Check if this driver currently has an active ride in progress
        $activeRide = Ride::where('driver_id', Auth::id())
            ->whereIn('status', ['accepted', 'picked_up'])
            ->first();

        // If they have an active ride, immediately bounce them back to the tracking map!
        if ($activeRide) {
            return redirect('/track/' . $activeRide->id);
        }

        // Otherwise, load colleges for the driver to pick a route
        $colleges = College::all();

        return view('components.pages.driver', [
            'colleges' => $colleges
        ]);
    }

    /**
     * Process the driver's route and assign them to the CLOSEST students.
     */
    public function matchWithStudent(Request $request)
    {
        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'available_slots' => 'required|integer|between:1,6',
            'driver_lat' => 'nullable|numeric|between:-90,90',
            'driver_long' => 'nullable|numeric|between:-180,180',
        ]);

        // 1. Grab the driver's exact GPS location sent from the frontend
        $driverLat = $request->input('driver_lat');
        $driverLng = $request->input('driver_long');

        // 2. Fallback: If GPS failed or was blocked, just get the first available students
        if (!$driverLat || !$driverLng) {
            $pendingRides = Ride::where('ending_point_college_id', $request->college_id)
                ->where('status', 'pending')
                ->whereNull('driver_id')
                ->orderBy('created_at', 'asc')
                ->limit($request->available_slots)
                ->get();
        } else {
            // 3. 🚨 THE MAGIC MATH: Find the closest students using the Haversine formula.
            // Done in PHP because SQLite has no acos/cos/sin/radians SQL functions.
            $pendingRides = Ride::where('ending_point_college_id', $request->college_id)
                ->where('status', 'pending')
                ->whereNull('driver_id')
                ->get()
                ->sortBy(fn (Ride $ride) => $this->distanceInKm($driverLat, $driverLng, $ride->pickup_lat, $ride->pickup_long)) // This orders them from closest to furthest!
                ->take((int) $request->available_slots);
        }

        // 4. If no students are waiting at all
        if ($pendingRides->isEmpty()) {
            return back()->with('error', 'No students are currently waiting for a ride to this destination.');
        }

        // 5. Assign the driver to these students
        Ride::whereIn('id', $pendingRides->pluck('id')->toArray())->update([
            'driver_id' => Auth::id(), // <-- Cleaned up!
            'status' => 'accepted'
        ]);

        // 6. Grab the ID of the absolute closest student they just matched with
        $firstRideId = $pendingRides->first()->id;

        // 7. Redirect the driver straight to the live tracking map for that specific student
        return redirect('/track/' . $firstRideId);
    }

    /**
     * Straight-line distance in km between two GPS points (Haversine formula).
     */
    private function distanceInKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 6371 * 2 * asin(sqrt($a));
    }


    public function trackRide($id)
    {
        // 1. Load BOTH the student and the college relationships
        $ride = Ride::with(['student', 'college'])
            ->findOrFail($id);

        // 2. 🚨 THE CARPOOL SAFETY CHECK 🚨
        // If this specific student is picked up, check if others are still waiting!
        if ($ride->status === 'picked_up') {

            $waitingStudent = Ride::where('driver_id', Auth::id()) // <-- Cleaned up!
                ->where('status', 'accepted')
                ->first();

            // If another student is waiting, force the map to route to them instead!
            if ($waitingStudent) {
                return redirect('/track/' . $waitingStudent->id)
                    ->with('error', 'You must pick up all students before heading to the college.');
            }
        }

        return view('components.pages.track-ride', [
            'ride' => $ride
        ]);
    }


    /**
     * Mark a student as picked up and route to the next one (or the college).
     */
    public function pickupStudent($id)
    {
        $ride = Ride::findOrFail($id);

        // 1. Mark this specific student as picked up
        $ride->update(['status' => 'picked_up']);

        // 2. Check if there are any MORE students waiting to be picked up
        $nextStudent = Ride::where('driver_id', Auth::id()) // <-- Cleaned up!
            ->where('status', 'accepted')
            ->first();

        // 3. If there is another student, route the map to them!
        if ($nextStudent) {
            return redirect('/track/' . $nextStudent->id)
                ->with('success', 'Student picked up! Routing to your next passenger...');
        }

        // 4. If NO more students are waiting, bounce them back to the map.
        // The Blade view will now switch to the College Route because everyone is picked up!
        return back()->with('success', 'All students picked up! Rerouting to college...');
    }


    /**
     * Drop off ALL students at the college and complete the ride.
     */
    public function dropoffStudent()
    {
        // Update ALL rides belonging to this driver that are currently in the car!
        Ride::where('driver_id', Auth::id()) // <-- Cleaned up!
            ->where('status', 'picked_up')
            ->update(['status' => 'completed']);

        return redirect('/driver')->with('success', 'All passengers dropped off successfully!');
    }

    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        // 1. Every student this driver has accepted or is carrying gets the update (carpools too)
        $rideIds = Ride::where('driver_id', Auth::id())
            ->whereIn('status', ['accepted', 'picked_up'])
            ->pluck('id')
            ->all();

        // 2. Fire the event to those students' browsers!
        if ($rideIds) {
            try {
                DriverLocationUpdated::dispatch($rideIds, $validated['lat'], $validated['lng']);
            } catch (BroadcastException) {
                // Reverb isn't running (php artisan reverb:start); the driver's own map still works
                return response()->json(['status' => 'Live tracking server is not running.'], 503);
            }
        }

        // 3. Return a quick success response back to the driver's phone
        return response()->json(['status' => 'Location broadcasted successfully!']);
    }
}
