<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\College;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{

    public function Allcolleges()
    {
        $colleges = College::all();

        return view('components.pages.driver', [
            'colleges' => $colleges
        ]);
    }

    /**
     * Process the driver's route and assign them to students.
     */
    public function matchWithStudent(Request $request)
    {
        // 1. Pass a single array of conditions into the first where()
        $pendingRides = Ride::where([
            'ending_point_college_id' => $request->college_id,
            'status' => 'pending'
        ])
            ->whereNull('driver_id')
            ->orderBy('created_at', 'asc')
            ->limit($request->available_slots)
            ->get();

        if ($pendingRides->isEmpty()) {
            return back()->with('error', 'No students are currently waiting for a ride to this destination.');
        }

        Ride::whereIn('id', $pendingRides->pluck('id')->toArray())->update([
            'driver_id' => Auth::id() ?? 1,
            'status' => 'accepted'
        ]);

        // Grab the ID of the first student they just matched with
        $firstRideId = $pendingRides->first()->id;

        // Redirect them straight to the live tracking map for that student!
        return redirect('/track/' . $firstRideId);
    }


    public function trackRide($id)
    {
        // 1. Load BOTH the student and the college relationships
        $ride = Ride::with(['student', 'college'])
            ->findOrFail($id);

        // 2. 🚨 THE CARPOOL SAFETY CHECK 🚨
        // If this specific student is picked up, check if others are still waiting!
        if ($ride->status === 'picked_up') {

            $waitingStudent = Ride::where('driver_id', Auth::id() ?? 1)
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
        $nextStudent = Ride::where('driver_id', Auth::id() ?? 2)
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
        // Since they are all going to the same college, we don't drop them off one by one.
        // We update ALL rides belonging to this driver that are currently in the car!
        Ride::where('driver_id', Auth::id() ?? 1)
            ->where('status', 'picked_up')
            ->update(['status' => 'completed']);

        return redirect('/driver')->with('success', 'All passengers dropped off successfully!');
    }
}
