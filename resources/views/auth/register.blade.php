<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LupusCare — Inscription</title>
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
            padding: 2rem 0;
        }

        .register-wrap {
            display: flex;
            width: 950px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(124,58,237,0.15);
        }

        .register-left {
            width: 320px;
            flex-shrink: 0;
            background: linear-gradient(180deg, #7C3AED 0%, #5B21B6 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            text-align: center;
        }

        .register-left img {
            height: 120px;
            margin-bottom: 1.5rem;
        }

        .register-left h2 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: .75rem;
        }

        .register-left p {
            color: #DDD6FE;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .register-left .steps {
            text-align: left;
            width: 100%;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #EDE9FE;
            font-size: 13px;
            margin-bottom: .75rem;
        }

        .step-dot {
            width: 24px;
            height: 24px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .register-right {
            flex: 1;
            background: white;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-right h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1E1B4B;
            margin-bottom: .25rem;
        }

        .register-right .subtitle {
            color: #6B7280;
            font-size: 13px;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #E5E7EB;
            padding: 10px 14px;
            font-size: 14px;
            transition: border .2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #7C3AED;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
            outline: none;
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .role-option input { display: none; }

        .role-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 14px;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
        }

        .role-label:hover { border-color: #A78BFA; background: #F5F3FF; }

        .role-option input:checked + .role-label {
            border-color: #7C3AED;
            background: #EDE9FE;
        }

        .role-icon { font-size: 24px; }
        .role-name { font-size: 13px; font-weight: 600; color: #1E1B4B; }
        .role-desc { font-size: 11px; color: #6B7280; }

        .btn-register {
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
            margin-top: .5rem;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(124,58,237,0.4);
        }

        .link-purple { color: #7C3AED; text-decoration: none; font-weight: 500; }
        .link-purple:hover { color: #5B21B6; }
    </style>
</head>
<body>

<div class="register-wrap">
    {{-- GAUCHE --}}
    <div class="register-left">
        <img src="{{ asset('images/logo.png') }}" alt="LupusCare">
        <h2>Rejoignez LupusCare</h2>
        <p>Créez votre compte en quelques secondes et commencez votre suivi médical.</p>
        <div class="steps">
            <div class="step">
                <div class="step-dot">1</div>
                Créez votre compte
            </div>
            <div class="step">
                <div class="step-dot">2</div>
                Complétez votre profil
            </div>
            <div class="step">
                <div class="step-dot">3</div>
                Prenez votre premier RDV
            </div>
            <div class="step">
                <div class="step-dot">4</div>
                Suivez vos symptômes
            </div>
        </div>
    </div>

    {{-- DROITE --}}
    <div class="register-right">
        <h3>Créer un compte</h3>
        <div class="subtitle">Remplissez les informations ci-dessous</div>

        {{-- Erreurs --}}
        @if($errors->any())
        <div class="alert alert-danger rounded-3 small mb-3">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- RÔLE --}}
            <div class="mb-3">
                <label class="form-label">Je suis</label>
                <div class="role-selector">
                    <div class="role-option">
                        <input type="radio" name="role" id="role_patient"
                               value="patient" {{ old('role', 'patient') === 'patient' ? 'checked' : '' }}>
                        <label class="role-label" for="role_patient">
                            <span class="role-icon"></span>
                            <span class="role-name">Patient</span>
                            <span class="role-desc">Je suis atteint de lupus</span>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" name="role" id="role_doctor"
                               value="doctor" {{ old('role') === 'doctor' ? 'checked' : '' }}>
                        <label class="role-label" for="role_doctor">
                            <span class="role-icon"></span>
                            <span class="role-name">Médecin</span>
                            <span class="role-desc">Je suis professionnel de santé</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" placeholder="Dr. Koné Amadou" required>
                    @error('name')
                    <div class="text-danger" style="font-size:12px">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" placeholder="votre@email.com" required>
                @error('email')
                <div class="text-danger" style="font-size:12px">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                    @error('password')
                    <div class="text-danger" style="font-size:12px">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmer</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-register">Créer mon compte</button>

            <div class="text-center mt-3" style="font-size:13px;color:#6B7280">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="link-purple">Se connecter</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>