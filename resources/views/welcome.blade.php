<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LupusCare — Suivi médical du lupus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin: 0; background: #F5F3FF; font-family: 'Segoe UI', sans-serif; }

        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 3rem;
            background: white;
            box-shadow: 0 1px 8px rgba(124,58,237,0.08);
        }

        .navbar .brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: #7C3AED;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar .nav-links {
            display: flex;
            gap: 12px;
        }

        .btn-outline {
            border: 1.5px solid #7C3AED;
            color: #7C3AED;
            background: transparent;
            border-radius: 10px;
            padding: 8px 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s;
        }

        .btn-outline:hover {
            background: #7C3AED;
            color: white;
        }

        .btn-primary {
            background: #7C3AED;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s;
        }

        .btn-primary:hover { background: #5B21B6; color: white; }

        .hero-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 4rem 2rem;
        }

        .hero-badge {
            background: #EDE9FE;
            color: #5B21B6;
            font-size: 13px;
            padding: 6px 16px;
            border-radius: 99px;
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            color: #1E1B4B;
            line-height: 1.2;
            margin-bottom: 1rem;
            max-width: 700px;
        }

        .hero-title span { color: #7C3AED; }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #6B7280;
            max-width: 550px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .hero-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, #7C3AED, #5B21B6);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(124,58,237,0.3);
            transition: all .2s;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(124,58,237,0.4);
            color: white;
        }

        .btn-hero-outline {
            border: 2px solid #7C3AED;
            color: #7C3AED;
            background: white;
            border-radius: 12px;
            padding: 14px 32px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all .2s;
        }

        .btn-hero-outline:hover {
            background: #EDE9FE;
            color: #5B21B6;
        }

        .features {
            background: white;
            padding: 5rem 3rem;
        }

        .features-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1E1B4B;
            margin-bottom: .5rem;
        }

        .features-sub {
            text-align: center;
            color: #6B7280;
            margin-bottom: 3rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .feature-card {
            background: #F5F3FF;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #7C3AED, #A78BFA);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 24px;
        }

        .feature-card h5 {
            font-size: 15px;
            font-weight: 600;
            color: #1E1B4B;
            margin-bottom: .5rem;
        }

        .feature-card p {
            font-size: 13px;
            color: #6B7280;
            line-height: 1.6;
        }

        .footer {
            background: #1E1B4B;
            color: #A78BFA;
            text-align: center;
            padding: 1.5rem;
            font-size: 13px;
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<div class="navbar">
   <div class="brand">
    <img src="{{ asset('images/logo.png') }}" alt="LupusCare" style="height:115px">
</div>
    <div class="nav-links">
        @auth
            <a href="{{ route('dashboard') }}" class="btn-primary">Mon espace</a>
        @else
            <a href="{{ route('login') }}" class="btn-outline">Se connecter</a>
            <a href="{{ route('register') }}" class="btn-primary">S'inscrire</a>
        @endauth
    </div>
</div>

{{-- HERO --}}
<div class="hero-content">
<div style="margin-bottom:2rem">
    <img src="{{ asset('images/logo.png') }}" alt="LupusCare" style="height:200px">
</div>
<div class="hero-badge"> Plateforme médicale spécialisée lupus</div>
<h1 class="hero-title">
    Suivez votre lupus avec<br>
    <span>intelligence et sérénité</span>
</h1>
    <p class="hero-subtitle">
        LupusCare connecte les patients lupiques et leurs médecins pour un suivi
        médical personnalisé, des rendez-vous simplifiés et un journal de symptômes
        complet.
    </p>
    <div class="hero-buttons">
        <a href="{{ route('register') }}" class="btn-hero-primary">Commencer gratuitement</a>
        <a href="{{ route('login') }}" class="btn-hero-outline">J'ai déjà un compte</a>
    </div>
</div>

{{-- FEATURES --}}
<div class="features">
    <h2 class="features-title">Tout ce dont vous avez besoin</h2>
    <p class="features-sub">Une plateforme pensée pour les patients et les médecins</p>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">📅</div>
            <h5>Rendez-vous simplifiés</h5>
            <p>Prenez et gérez vos rendez-vous en ligne selon les disponibilités de votre médecin.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h5>Suivi des symptômes</h5>
            <p>Enregistrez vos symptômes quotidiennement et visualisez votre évolution sur des graphiques.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔔</div>
            <h5>Notifications en temps réel</h5>
            <p>Recevez des alertes pour vos rendez-vous, confirmations et messages de votre médecin.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📋</div>
            <h5>Journal médical</h5>
            <p>Votre médecin rédige des notes de suivi après chaque consultation, accessibles à tout moment.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🏥</div>
            <h5>Médecins spécialisés</h5>
            <p>Consultez des rhumatologues et néphrologues spécialisés dans la prise en charge du lupus.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">⚠️</div>
            <h5>Alerte poussée</h5>
            <p>Signalez une poussée en un clic et alertez automatiquement votre médecin référent.</p>
        </div>
    </div>
</div>

{{-- FOOTER --}}
<div class="footer">
    © {{ date('Y') }} LupusCare — Plateforme de suivi médical du lupus érythémateux systémique
</div>

</body>
</html>