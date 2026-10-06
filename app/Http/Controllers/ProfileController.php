<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile page (opened from the sidebar).
     */
    public function edit()
    {
        return view('components.pages.profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user's name and email.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Email must stay unique, but keeping your own current email is fine
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
        ]);

        Auth::user()->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change the user's password (they must know their current one).
     */
    public function updatePassword(Request $request)
    {
        // Separate error bag so these errors show under the password form, not the profile form
        $validated = $request->validateWithBag('password', [
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}
