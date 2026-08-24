<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LupusCare — @yield('title', 'Tableau de bord')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

{{-- SIDEBAR --}}
<div class="lc-sidebar">
    <div class="brand">
        <i class="bi bi-heart-pulse-fill"></i> LupusCare
    </div>
    <nav class="nav flex-column">

        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Tableau de bord
        </a>

        <a href="/rendezvous" class="nav-link {{ request()->is('rendezvous*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i> Rendez-vous
        </a>
        @if(auth()->user()->isDoctor())
        <a href="{{ route('disponibilites.index') }}" class="nav-link {{ request()->is('disponibilites*') ? 'active' : '' }}">
            <i class="bi bi-clock-fill"></i> Disponibilités
        </a>
        @endif

        @if(auth()->user()->isPatient())
        <a href="/symptoms" class="nav-link {{ request()->is('symptoms*') ? 'active' : '' }}">
            <i class="bi bi-activity"></i> Mes symptômes
        </a>
        @endif

        @if(auth()->user()->isDoctor())
        <a href="/patients" class="nav-link {{ request()->is('patients*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Mes patients
        </a>
        @endif

        <a href="/notifications" class="nav-link {{ request()->is('notifications*') ? 'active' : '' }}">
            <i class="bi bi-bell-fill"></i> Notifications
            @if(auth()->user()->unreadNotifications->count())
                <span class="badge bg-danger ms-auto">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>

        <a href="/profile" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i> Mon profil
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.index') }}" class="nav-link {{ request()->is('admin*') ? 'active' : '' }}">
            <i class="bi bi-shield-check-fill"></i> Administration
        </a>
        @endif
    </nav>

    {{-- Déconnexion en bas --}}
    <div style="position:absolute; bottom:2rem; left:1rem; right:1rem;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                <i class="bi bi-box-arrow-left"></i> Déconnexion
            </button>
        </form>
    </div>
</div>

{{-- TOPBAR --}}
<div class="lc-topbar">
    <h6 class="mb-0 fw-semibold">@yield('title', 'Tableau de bord')</h6>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">{{ auth()->user()->name }}</span>
        <span class="badge" style="background:var(--lc-accent);color:var(--lc-primary);">
            {{ auth()->user()->isDoctor() ? 'Médecin' : 'Patient' }}
        </span>
    </div>
</div>

{{-- CONTENU --}}
<div class="lc-main">
    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 mb-3">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @yield('content')
</div>

</body>
</html>