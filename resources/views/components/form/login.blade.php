<div id="form-login" class="auth-form-panel fade-enter">
    <form action="/login" method="POST">
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-input" name="email" placeholder="you@example.com" required>
        </div>

        <!-- Password -->
        <div class="form-group">
            <div class="flex justify-between items-center mb-2">
                <label class="form-label mb-0!">Password</label>
            </div>
            <input type="password" class="form-input" name="password" placeholder="••••••••" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="form-btn">
            Sign In
        </button>
    </form>
</div>
