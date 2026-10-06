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
        // The pure Model method for inserting data, pulling directly from the request
        Ride::create([
            'student_id' => Auth::id(),
            'driver_id' => null,
            'ending_point_college_id' => $request->college_id,
            'pickup_lat' => $request->pickup_lat,
            'pickup_long' => $request->pickup_long,
            'status' => 'pending'
        ]);

        return redirect('/search-driver');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
