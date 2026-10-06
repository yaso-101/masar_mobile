<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function Allcolleges()
    {
        $user = Auth::user();

        // 🚕 DRIVER VIEW LOGIC
        // If a driver clicks the student tab, just send them to the page to see the blank placeholder.
        if ($user && $user->hasRole('driver')) {
            return view('components.card-student'); // Replace with your actual student blade file name
        }

        // 🎒 STUDENT VIEW LOGIC
        // 1. THE BOUNCER: Check if this student already has an active ride in progress
        $activeRide = Ride::where('student_id', Auth::id())
            ->whereIn('status', ['pending', 'accepted', 'picked_up'])
            ->first();

        // 2. If they have an active ride, bounce them straight to their current screen!
        if ($activeRide) {
            // Still waiting for a driver? Send to search screen.
            if ($activeRide->status === 'pending') {
                return redirect('/search-driver/' . $activeRide->id);
            }
            // Driver found? Send straight to the tracking map!
            return redirect('/found-ride/' . $activeRide->id);
        }

        // 3. Otherwise, load colleges for the student to select
        $colleges = College::all();

        return view('components.pages.student', [
            'colleges' => $colleges
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function storeStudentFirst(Request $request)
    {
        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'pickup_lat' => 'required|numeric|between:-90,90',
            'pickup_long' => 'required|numeric|between:-180,180',
        ], [
            'pickup_lat.required' => 'Please select your pickup location on the map.',
            'pickup_long.required' => 'Please select your pickup location on the map.',
        ]);

        // 1. Save the newly created ride into a variable
        $ride = Ride::create([
            'student_id' => Auth::id(),
            'driver_id' => null,
            'ending_point_college_id' => $request->college_id,
            'pickup_lat' => $request->pickup_lat,
            'pickup_long' => $request->pickup_long,
            'status' => 'pending'
        ]);

        // 2. Redirect to the search screen AND pass the new ride ID!
        return redirect('/search-driver/' . $ride->id);
    }

    /**
     * Show the searching screen for a specific ride.
     */
    public function searchDriver(Ride $ride)
    {
        // Security check: Only let the student see their own search screen!
        if ($ride->student_id !== Auth::id()) {
            abort(404);
        }

        return view('components.pages.search-driver', [
            'ride' => $ride
        ]);
    }

    /**
     * API: Check if a driver has accepted the student's ride.
     */
    public function checkRideStatus(Ride $ride)
    {
        // Security check
        if ($ride->student_id !== Auth::id()) {
            abort(404);
        }

        return response()->json([
            'status' => $ride->status,
            'has_driver' => $ride->driver_id !== null,
        ]);
    }

    /**
     * Show the live map view once a driver is assigned.
     */
    public function foundRide(\App\Models\Ride $ride)
    {
        // Security check
        if ($ride->student_id !== Auth::id()) {
            abort(404);
        }

        // Pass the active ride to the found-ride map file
        return view('components.pages.found-ride', [
            'ride' => $ride
        ]);
    }


    public function destroy(User $user)
    {
        //
    }
}
