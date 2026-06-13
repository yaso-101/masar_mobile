<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Native\Mobile\Facades\System;

class LoggedUserController extends Controller
{

    public function store(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password' => 'The provided credentials do not match our records.',
        ]);
    }

    public function create()
    {
        return view('components.form.auth');
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
