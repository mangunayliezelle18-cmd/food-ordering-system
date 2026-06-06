<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍕 Food Ordering Admin System</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <span class="text-xl font-bold tracking-wider text-amber-400">🍕 FoodOrder Admin</span>
                    <div class="hidden md:flex space-x-2">
                        <x-nav-link href="/admin/menu" :active="request()->is('admin/menu*')">Menu Management</x-nav-link>
                    </div>
                </div>
                <div class="text-sm text-gray-400">Final Project Panel</div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

</body>
</html>