<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Halcon Web Application')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="{{ route('home') }}">Halcon</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Track order</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('archived-orders.index') }}">Archived Orders</a></li>
                    @endauth
                </ul>
                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @else
                        <li class="nav-item"><span class="navbar-text mr-3">{{ Auth::user()->name }}</span></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
