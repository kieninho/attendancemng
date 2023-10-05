<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <header>
        <nav>
            <!-- Định nghĩa menu -->
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- Định nghĩa footer -->
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
