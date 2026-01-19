<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MASUK - Tunas Tirta Fresh</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .input-focus:focus {
            outline: none;
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 font-inter antialiased">
    <div class="w-full max-w-md bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-8 pt-10 pb-6 border-b border-gray-200 text-center">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                MASUK
            </h1>
            <p class="text-gray-600 mt-1.5 text-sm">
                Akun Pelanggan
            </p>
        </div>

        <!-- Form -->
        <form class="px-8 pt-8 pb-10 space-y-7">
            <!-- Email / Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email / Nomor Telepon
                </label>
                <div class="relative">
                    <input 
                        type="text"
                        name="email"
                        class="w-full px-4 py-3.5 text-base border border-gray-300 rounded-md input-focus transition"
                        placeholder=""
                        required
                    />
                    <!-- Icon custom (bisa diganti sesuai kebutuhan) -->
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 5C13.66 5 15 6.34 15 8C15 9.66 13.66 11 12 11C10.34 11 9 9.66 9 8C9 6.34 10.34 5 12 5ZM12 19.2C9.5 19.2 7.29 17.92 6 15.98C6.03 13.99 10 12.9 12 12.9C13.99 12.9 17.97 13.99 18 15.98C16.71 17.92 14.5 19.2 12 19.2Z" fill="#6B7280"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-baseline mb-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Kata Sandi
                    </label>
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition">
                        Lupa?
                    </a>
                </div>
                <input 
                    type="password"
                    name="password"
                    class="w-full px-4 py-3.5 text-base border border-gray-300 rounded-md input-focus transition"
                    placeholder=""
                    required
                />
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold text-base py-3.5 rounded-md transition-colors duration-200 mt-2"
            >
                MASUK
            </button>
        </form>

        <!-- Divider -->
        <div class="px-8">
            <div class="relative py-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="bg-white px-4 text-gray-500">atau</span>
                </div>
            </div>
        </div>

        <!-- Google Button -->
        <div class="px-8 pb-10">
            <a 
                href="{{ route('google.login') }}"
                class="flex items-center justify-center gap-3 w-full border border-gray-300 hover:border-gray-400 bg-white text-gray-700 font-medium py-3.5 rounded-md transition-colors duration-200"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 border-t border-gray-200 px-8 py-5 text-center text-sm text-gray-600">
            Hubungi admin untuk pembuatan akun baru
        </div>
    </div>
</body>
</html>