<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') / Awesome Shoestore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>
</head>

<body class="h-full flex flex-col">

    @include('layouts.includes.user-menu')

    @include('layouts.includes.header')
    <div id="notification-container" class="fixed top-4 right-4 z-50">
        @if (session()->has('success'))
            <div id="success-alert"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg transition-opacity duration-300"
                role="alert">
                <div class="flex items-center">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button onclick="closeAlert(this.parentElement.parentElement)" class="ml-4">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div id="error-alert"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg transition-opacity duration-300"
                role="alert">
                <div class="flex items-center">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button onclick="closeAlert(this.parentElement.parentElement)" class="ml-4">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
    <div class="container mx-auto px-8 flex-1">
        @yield('content')
    </div>

    @include('layouts.includes.footer')

    <script>
        function closeAlert(element) {
            element.style.opacity = '0';
            setTimeout(() => {
                element.style.display = 'none';
            }, 300);
        }

        // Auto-dismiss alerts after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    closeAlert(alert);
                }, 3000);
            });
        });
    </script>
</body>

</html>
