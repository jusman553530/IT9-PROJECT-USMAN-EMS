<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">

    <!-- CONTAINER (FIXED WIDTH PROPERLY) -->
    <div class="w-full max-w-md">

        <!-- CARD -->
        <div class="bg-white rounded-xl border shadow-lg p-8">

            <!-- LOGO + TITLE -->
            <div class="text-center mb-8">

                <!-- LOGO BOX -->
                <div class="mx-auto mb-4">
                    <img src="/logo.png" class="w-30 h-30 object-contain mx-auto"
                         alt="EMS Logo">
                </div>

                <h1 class="text-xl font-bold mb-2">
                    Employee Management System
                </h1>

                <p class="text-gray-500 text-sm">
                    Sign in to your account
                </p>

            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm mb-2">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="admin@company.com"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C521C]"
                        required
                    >
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block text-sm mb-2">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C521C]"
                        required
                    >
                </div>

                <!-- REMEMBER -->
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="w-full text-white py-3 rounded-lg transition hover:opacity-90"
                    style="background-color: #0C521C;"
                >
                    Sign In
                </button>

            </form>

        </div>

        <!-- FOOTER -->
        <p class="text-center text-sm text-gray-500 mt-8">
            © 2026 Employee Management System
        </p>

    </div>

</div>

</body>
</html>