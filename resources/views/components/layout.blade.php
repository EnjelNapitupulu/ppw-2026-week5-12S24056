<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SIPUS-Del' }} — Institut Teknologi Del</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-del { background-color: #4C1D95; }
        .btn-del { background-color: #4C1D95; color: #fff; }
        .btn-del:hover { background-color: #3B0764; color: #fff; }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark navbar-del shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('buku.index') }}">SIPUS-Del</a>
        <div class="navbar-nav flex-row gap-3 align-items-center">
            <a class="nav-link" href="{{ route('buku.index') }}">Buku</a>
            @auth
                <a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a>
                <a class="nav-link" href="{{ route('peminjaman.index') }}">Peminjaman</a>
                <span class="navbar-text small text-white-50">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            @else
                <a class="nav-link" href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </div>
</nav>
<div class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @auth
        @if(session('login_timestamp') || session('last_search'))
            <div class="small text-muted mb-3">
                @if(session('login_timestamp'))Login sejak: {{ session('login_timestamp') }}@endif
                @if(session('last_search')) &middot; Pencarian terakhir: <em>{{ session('last_search') }}</em>@endif
            </div>
        @endif
    @endauth

    {{ $slot }}
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>