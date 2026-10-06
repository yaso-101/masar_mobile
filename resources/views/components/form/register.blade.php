<div id="form-signup" class="auth-form-panel hidden-panel">
    <form action="/register" method="POST">
        @csrf

        <!-- UPDATED: Changed name to 'role' and value to 'student' -->
        <input type="hidden" id="role-input" name="role" value="{{ old('role', 'student') }}">

        <!-- ROLE SELECTOR -->
        <div class="role-selector">

            <!-- GUARDIAN -->
            <div id="role-btn-guardian" class="role-btn" onclick="selectRole('guardian')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="role-label">Guardian</span>
            </div>

            <!-- STUDENT -->
            <div id="role-btn-student" class="role-btn active" onclick="selectRole('student')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
                <span class="role-label">Student</span>
            </div>

            <!-- DRIVER -->
            <div id="role-btn-driver" class="role-btn" onclick="selectRole('driver')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 12C12 12 8 12 5 12C5 16 8 19 12 19" />
                    <path d="M12 12C12 12 16 12 19 12C19 16 16 19 12 19" />
                </svg>
                <span class="role-label">Driver</span>
            </div>

        </div>
        @error('role', 'register')
            <span class="form-error mb-4">{{ $message }}</span>
        @enderror

        <!-- Name -->
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-input" name="name" value="{{ old('name') }}" placeholder="Your Name"
                required>
            @error('name', 'register')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-input" name="email" value="{{ old('email') }}"
                placeholder="you@example.com" required>
            @error('email', 'register')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Phone Number (the FIB number this user will pay the subscription from) -->
        <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" class="form-input" name="phone_number" value="{{ old('phone_number') }}"
                placeholder="07XX XXX XXXX" inputmode="tel" autocomplete="tel" dir="ltr" required>
            @error('phone_number', 'register')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" class="form-input" name="password" placeholder="Create a password" required>
            @error('password', 'register')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="form-btn">
            Create Account
        </button>
    </form>
</div>
