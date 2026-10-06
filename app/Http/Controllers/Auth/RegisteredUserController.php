<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        // 0. Store every phone number the same way ("+964 770 123 4567" -> "07701234567")
        $request->merge(['phone_number' => Subscription::normalizePhone($request->phone_number)]);

        // 1. Validate 'role' as a string instead of an integer ID
        // ('register' error bag so the errors show on the Sign Up form, not the Login form)
        $request->validateWithBag('register', [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:driver,student,guardian',
            'phone_number' => 'required|regex:/^07\d{9}$/|unique:subscriptions,phone_number',
        ], [
            'phone_number.regex' => 'Enter a valid Iraqi mobile number, e.g. 0770 123 4567.',
            'phone_number.unique' => 'This phone number is already registered.',
        ]);

        // 2. Create the user, their Spatie role and their (unpaid) subscription all together
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->role);

            $user->subscription()->create([
                'phone_number' => $request->phone_number,
                'is_paid' => false,
            ]);

            return $user;
        });

        // 3. Log them in
        Auth::login($user);

        // 4. New accounts aren't paid yet, so they start on the subscription (pay with FIB) page
        return redirect('/subscription');
    }

    public function create()
    {
        return view('components.form.auth');
    }

    public function destroy(User $user)
    {
        //
    }
}
