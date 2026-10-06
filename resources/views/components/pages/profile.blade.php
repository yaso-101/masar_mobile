<x-layout>
    <div class="min-h-screen bg-slate-50 p-4 pb-24">
        <div class="max-w-md mx-auto">

            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-slate-800">Your Profile</h1>
                <p class="text-slate-500 mt-2">Update your details and password.</p>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 👤 PROFILE DETAILS -->
            <form action="/profile" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-6">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-bold text-slate-800 mb-4">Profile Details</h2>

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="form-btn">Save Changes</button>
            </form>

            <!-- 🔒 CHANGE PASSWORD -->
            <form action="/profile/password" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-bold text-slate-800 mb-4">Change Password</h2>

                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-input"
                        autocomplete="current-password" required>
                    @error('current_password', 'password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password" class="form-input"
                        placeholder="At least 8 characters" autocomplete="new-password" required>
                    @error('password', 'password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                        autocomplete="new-password" required>
                </div>

                <button type="submit" class="form-btn">Update Password</button>
            </form>

        </div>
    </div>
</x-layout>
