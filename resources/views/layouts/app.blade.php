<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Tìm chuyến bay phù hợp cùng SkyWings.')">
    <title>@yield('title', 'SkyWings')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="@yield('body-class')">
    <header>
        <nav class="navbar navbar-expand-lg bg-white py-3" aria-label="Điều hướng chính">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">SkyWings</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Mở menu điều hướng">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavigation">
                    <ul class="navbar-nav ms-auto my-3 my-lg-0 gap-lg-3">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('flights.index') }}">Chuyến bay</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @if (session('status'))
        <div class="container mt-3">
            <div class="alert alert-success mb-0" role="status">{{ session('status') }}</div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
