<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LupusCare — Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #F5F3FF 0%, #EDE9FE 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-wrap {
            display: flex;
            width: 900px;
            min-height: 520px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(124,58,237,0.15);
        }

        .login-left {
            flex: 1;
            background: linear-gradient(180deg, #7C3AED 0%, #5B21B6 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            text-align: center;
        }

        .login-left img {
            height: 120px;
            margin-bottom: 1.5rem;
        }

        .login-left h2 {
            color: white;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: .75rem;
        }

        .login-left p {
            color: #DDD6FE;
            font-size: 14px;
            line-height: 1.7;
        }

        .login-right {
            flex: 1;
            background: white;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1E1B4B;
            margin-bottom: .25rem;
        }

        .login-right .subtitle {
            color: #6B7280;
            font-size: 13px;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #E5E7EB;
            padding: 10px 14px;
            font-size: 14px;
            transition: border .2s;
        }

        .form-control:focus {
            border-color: #7C3AED;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
            outline: none;
        }

        .btn-login {
            background: linear-gradient(135deg, #7C3AED, #5B21B6);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all .2s;
            box-shadow: 0 4px 15px rgba(124,58,237,0.3);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(124,58,237,0.4);
        }

        .link-purple { color: #7C3AED; text-decoration: none; font-weight: 500; }
        .link-purple:hover { color: #5B21B6; }

        .divider {
            text-align: center;
            color: #9CA3AF;
            font-size: 13px;
            margin: 1.25rem 0;
            position: relative;
        }

        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #E5E7EB;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }
    </style>
</head>
<body>

<div class="login-wrap">
    {{-- GAUCHE --}}
    <div class="login-left">
        <img src="{{ asset('images/logo.png') }}" alt="LupusCare">
        <h2>Bienvenue sur LupusCare</h2>
        <p>
            Plateforme de suivi médical dédiée aux patients lupiques
            et à leurs médecins spécialistes.
        </p>
    </div>

    {{-- DROITE --}}
    <div class="login-right">
        <h3>Connexion</h3>
        <div class="subtitle">Entrez vos identifiants pour accéder à votre espace</div>

        {{-- Erreurs --}}
        @if($errors->any())
        <div class="alert alert-danger rounded-3 small mb-3">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}"
                       placeholder="votre@email.com" required autofocus>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-label">Mot de passe</label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link-purple" style="font-size:12px">
                        Mot de passe oublié ?
                    </a>
                    @endif
                </div>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required>
            </div>

            <div class="d-flex align-items-center gap-2 mb-4">
                <input type="checkbox" name="remember" id="remember" class="form-check-input m-0">
                <label for="remember" class="form-label m-0" style="font-weight:400;color:#6B7280">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>

            <div class="divider">ou</div>

            <div class="text-center" style="font-size:13px;color:#6B7280">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="link-purple">S'inscrire</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>