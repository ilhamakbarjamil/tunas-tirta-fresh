<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tunas Tirta Fresh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#00A859', dark: '#008248', light: '#F0F9F5' },
                        border: { DEFAULT: '#E5E7EB', dark: '#D1D5DB' }
                    },
                    boxShadow: {
                        'input': 'inset 0 1px 2px rgba(0, 0, 0, 0.05)',
                        'button': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-lg shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="pt-6 px-6 pb-2">
            <div class="flex justify-center mb-1">
                <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center">
                    <span class="text-2xl">🍏</span>
                </div>
            </div>
            <h1 class="text-xl font-bold text-center text-gray-800">Masuk ke Akun Anda</h1>
            <p class="text-gray-500 text-sm text-center mt-1">Silakan masuk untuk mulai belanja</p>
        </div>

        <!-- Form -->
        <form class="px-6 pt-4 pb-5">
            <div class="space-y-4">
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email atau Nomor Telepon
                    </label>
                    <input 
                        type="text" 
                        id="email" 
                        placeholder="contoh: email@domain.com"
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-md shadow-input focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Kata Sandi
                        </label>
                        <a href="#" class="text-xs text-primary font-medium hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        placeholder="Masukkan kata sandi"
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-md shadow-input focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-md transition duration-150 shadow-button mt-2"
                >
                    Masuk
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="px-6 py-2">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-2 bg-white text-gray-500">atau masuk dengan</span>
                </div>
            </div>
        </div>

        <!-- Google Login -->
        <div class="px-6 pb-6">
            <a 
                href="{{ route('google.login') }}" 
                class="flex items-center justify-center gap-3 w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 rounded-md transition duration-150 shadow-button"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Google
            </a>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-100 bg-gray-50 px-6 py-4 rounded-b-lg">
            <div class="text-center text-sm text-gray-600">
                Hubungi admin untuk membuat akun baru.
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-gray-700 hover:underline">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>