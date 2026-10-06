<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-emerald-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-black-700 tracking-tight">Welcome</h1>
        </div>

        <!-- Main White Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-emerald-100">

            <!-- SLIDER TOGGLE -->
            <div class="auth-toggle">
                <div id="auth-slider" class="auth-slider-bg"></div>
                <div id="tab-login" class="auth-tab-btn active" onclick="switchAuth('login')">Login</div>
                <div id="tab-signup" class="auth-tab-btn" onclick="switchAuth('signup')">Sign Up</div>
            </div>

            <!-- INCLUDE THE SEPARATE FORM FILES HERE -->


            @include('components.form.login')


            @include('components.form.register')

            @if ($errors->register->any())
                <!-- Sign up failed validation: reopen the Sign Up tab with the role they picked -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        switchAuth('signup');
                        selectRole(@json(old('role', 'student')));
                    });
                </script>
            @endif

        </div>

        <!-- Footer Text -->
        <p class="text-center text-gray-400 text-xs mt-6">
            By continuing, you agree to our Terms of Service.
        </p>

    </div>

    <!-- No script tag needed here, it's in form.js -->
</body>
</html>
