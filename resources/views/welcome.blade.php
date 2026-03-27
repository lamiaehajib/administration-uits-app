<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'administration-uits') }}</title>
    <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --red:        #C62828;
            --red-dark:   #8E0000;
            --red-xdark:  #5a0000;
            --ink:        #1A1A2E;
            --muted:      #6B7280;
            --border:     #E5E7EB;
            --bg-input:   #F9FAFB;

            --fs-xs:   clamp(9px,  .85vw, 12px);
            --fs-sm:   clamp(10px, 1vw,   13px);
            --fs-base: clamp(12px, 1.1vw, 14px);
            --fs-md:   clamp(13px, 1.2vw, 16px);
            --fs-lg:   clamp(16px, 1.9vw, 24px);
            --fs-xl:   clamp(20px, 2.8vw, 38px);

            --radius-sm: 6px;
            --radius:    10px;
            --radius-lg: 16px;
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── LAYOUT ── */
        .page {
            display: flex;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════════
           LEFT PANEL — justify-content: center
           pour que tout soit centré verticalement
        ══════════════════════════════════════════ */
        .left {
            flex: 0 0 46%;
            position: relative;
            background: var(--red-dark);
            display: flex;
            flex-direction: column;
            justify-content: center;          /* ← centrage vertical */
            padding: clamp(24px, 4vw, 56px) clamp(20px, 4vw, 52px);
            overflow: hidden;
        }

        /* Grid texture */
        .left::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px);
            background-size: clamp(22px, 3vw, 40px) clamp(22px, 3vw, 40px);
            pointer-events: none;
        }

        /* Top-right circle */
        .left::after {
            content: '';
            position: absolute;
            width: clamp(140px, 26vw, 360px);
            height: clamp(140px, 26vw, 360px);
            border-radius: 50%;
            border: clamp(22px, 3.8vw, 52px) solid rgba(255,255,255,.07);
            top:   clamp(-65px, -8.5vw, -18px);
            right: clamp(-65px, -8.5vw, -18px);
            pointer-events: none;
        }

        /* Bottom-right small circle */
        .circle2 {
            position: absolute;
            width:  clamp(80px, 13vw, 180px);
            height: clamp(80px, 13vw, 180px);
            border-radius: 50%;
            border: clamp(12px, 2vw, 28px) solid rgba(255,255,255,.05);
            bottom: clamp(28px, 4.5vw, 64px);
            right:  clamp(10px, 1.8vw, 26px);
            pointer-events: none;
        }

        /* Accent bar */
        .left-accent {
            position: absolute;
            width: 3px; height: 40%;
            background: linear-gradient(180deg, rgba(255,255,255,.18), transparent);
            top: 10%; left: clamp(28px, 4vw, 52px);
            border-radius: 99px; pointer-events: none;
        }

        /* Content wrapper — z-index above decorations */
        .left-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* Badge */
        .badge {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 999px;
            padding: .35em .9em;
            font-size: var(--fs-xs);
            font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
            color: rgba(255,255,255,.82);
            width: fit-content;
            margin-bottom: clamp(14px, 2.2vw, 28px);
            backdrop-filter: blur(4px);
        }
        .badge-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #7EE787;
            box-shadow: 0 0 6px #7EE787;
            display: inline-block;
            animation: pulse 2.4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: .55; transform: scale(.82); }
        }

        /* Heading */
        .left-title {
            font-size: var(--fs-xl);
            font-weight: 800; color: #fff;
            line-height: 1.18; letter-spacing: -.025em;
            margin-bottom: clamp(8px, 1.3vw, 16px);
        }
        .left-title em { font-style: normal; color: rgba(255,255,255,.42); }
        .left-title .highlight {
            position: relative; display: inline-block; color: #fff;
        }
        .left-title .highlight::after {
            content: '';
            position: absolute;
            left: 0; bottom: 3px;
            width: 100%; height: 3px;
            background: rgba(255,255,255,.25);
            border-radius: 2px;
        }

        /* Description */
        .left-desc {
            font-size: var(--fs-sm);
            color: rgba(255,255,255,.52); line-height: 1.7;
            max-width: 340px;
            margin-bottom: clamp(20px, 2.8vw, 40px);
        }

        /* Features */
        .features { display: flex; flex-direction: column; gap: clamp(7px, 1.1vw, 13px); }

        .feat {
            display: flex; align-items: center; gap: clamp(8px, 1vw, 12px);
            font-size: var(--fs-sm); font-weight: 500;
            color: rgba(255,255,255,.7);
        }
        .feat-icon {
            width:  clamp(22px, 2.6vw, 32px);
            height: clamp(22px, 2.6vw, 32px);
            border-radius: clamp(5px, .65vw, 8px);
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: center;
            font-size: clamp(10px, 1.2vw, 14px); flex-shrink: 0;
        }

        /* Watermark */
        .left-watermark {
            position: absolute; z-index: 2;
            bottom: clamp(16px, 2.2vw, 28px);
            left:   clamp(20px, 4vw, 52px);
            font-size: var(--fs-xs);
            font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            color: rgba(255,255,255,.22);
        }

        /* ══════════════════════════════════════════
           RIGHT PANEL
        ══════════════════════════════════════════ */
        .right {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            background: #fff;
            padding: clamp(20px, 3vw, 48px) clamp(16px, 4vw, 60px);
            position: relative;
        }

        .right::before {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(198,40,40,.04) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .form-wrap {
            position: relative; z-index: 1;
            width: 100%;
            max-width: clamp(280px, 34vw, 420px);
        }

        /* Brand */
        .brand {
            display: flex; align-items: center; gap: clamp(9px, 1.1vw, 14px);
            margin-bottom: clamp(24px, 3.5vw, 44px);
            padding-bottom: clamp(18px, 2.4vw, 30px);
            border-bottom: 1.5px solid var(--border);
        }
        .brand-logo-wrap {
            width:  clamp(34px, 3.4vw, 50px);
            height: clamp(34px, 3.4vw, 50px);
            border-radius: var(--radius-sm);
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
            flex-shrink: 0;
        }
        .brand-logo-wrap img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .brand-name {
            font-size: var(--fs-md); font-weight: 800;
            color: var(--ink); letter-spacing: -.015em; line-height: 1.1;
        }
        .brand-name span { color: var(--red); }
        .brand-sub {
            font-size: var(--fs-xs); color: var(--muted);
            letter-spacing: .06em; text-transform: uppercase; margin-top: 4px;
            font-weight: 500;
        }

        /* Heading */
        .login-eyebrow {
            font-size: var(--fs-xs); font-weight: 700;
            color: var(--red); letter-spacing: .1em; text-transform: uppercase;
            margin-bottom: 6px;
        }
        .login-h {
            font-size: var(--fs-lg); font-weight: 800;
            color: var(--ink); letter-spacing: -.025em; line-height: 1.15;
            margin-bottom: 6px;
        }
        .login-sub {
            font-size: var(--fs-sm); color: var(--muted); line-height: 1.55;
            margin-bottom: clamp(18px, 2.6vw, 30px);
        }

        /* Fields */
        .field { margin-bottom: clamp(12px, 1.5vw, 18px); }
        .field label {
            display: flex; align-items: center; gap: 5px;
            font-size: var(--fs-xs); font-weight: 700;
            color: var(--ink); letter-spacing: .06em; text-transform: uppercase;
            margin-bottom: 7px;
        }
        .field label svg { opacity: .45; flex-shrink: 0; }
        .field input {
            width: 100%;
            padding: clamp(9px, 1.1vw, 13px) clamp(11px, 1.2vw, 14px);
            border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-size: var(--fs-base); font-family: inherit;
            color: var(--ink); background: var(--bg-input);
            transition: border-color .2s, box-shadow .2s, background .2s; outline: none;
        }
        .field input:focus {
            border-color: var(--red); background: #fff;
            box-shadow: 0 0 0 3.5px rgba(198,40,40,.10);
        }
        .field input::placeholder { color: #C2C7D0; }
        .field-error { font-size: var(--fs-xs); color: var(--red); margin-top: 5px; }

        /* Row */
        .form-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: clamp(14px, 1.8vw, 22px); flex-wrap: wrap; gap: 8px;
        }
        .remember {
            display: flex; align-items: center; gap: 8px;
            font-size: var(--fs-sm); color: var(--muted);
            cursor: pointer; user-select: none;
        }
        .remember input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--red); cursor: pointer; }
        .forgot {
            font-size: var(--fs-sm); font-weight: 600;
            color: var(--red); text-decoration: none; white-space: nowrap;
            transition: color .2s;
        }
        .forgot:hover { color: var(--red-dark); text-decoration: underline; }

        /* Button */
        .btn {
            width: 100%;
            padding: clamp(10px, 1.3vw, 14px);
            background: var(--red); color: #fff;
            border: none; border-radius: var(--radius-sm);
            font-size: var(--fs-base); font-weight: 700; font-family: inherit;
            cursor: pointer; letter-spacing: .01em;
            transition: background .2s, transform .12s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(198,40,40,.28);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn:hover  { background: var(--red-dark); box-shadow: 0 6px 22px rgba(198,40,40,.38); }
        .btn:active { transform: scale(.985); }

        /* Security note */
        .security-note {
            display: flex; align-items: center; gap: 8px;
            background: #FFF5F5;
            border: 1px solid rgba(198,40,40,.12);
            border-radius: var(--radius-sm);
            padding: clamp(9px, 1vw, 12px) clamp(10px, 1.1vw, 14px);
            margin-bottom: clamp(14px, 1.8vw, 20px);
        }
        .security-note svg { flex-shrink: 0; color: var(--red); opacity: .7; }
        .security-note p { font-size: var(--fs-xs); color: var(--muted); line-height: 1.5; }
        .security-note strong { color: var(--ink); }

        /* Footer */
        .copy {
            text-align: center;
            font-size: var(--fs-xs); color: #C2C7D0;
            margin-top: clamp(14px, 2vw, 24px);
        }
        .copy strong { color: var(--red); font-weight: 700; }

        /* ── MOBILE ── */
        @media (max-width: 680px) {
            .page { flex-direction: column; }
            .left { flex: none; min-height: 200px; justify-content: flex-end; padding: 24px 22px; }
            .left-desc, .features, .left-watermark, .left-accent { display: none; }
            .left-title { font-size: 22px; }
            .right { background: #F7F8FC; padding: 28px 18px; }
            .form-wrap {
                background: #fff; border-radius: var(--radius-lg);
                padding: 26px 22px;
                box-shadow: 0 4px 28px rgba(0,0,0,.09);
                max-width: 100%;
            }
            .right::before { display: none; }
        }
    </style>
</head>
<body>
<div class="page">

    <!-- ══ LEFT ══ -->
    <div class="left">
        <div class="circle2"></div>
        <div class="left-accent"></div>

        <div class="left-content">
            <div class="badge">
                <span class="badge-dot"></span>
                Système opérationnel
            </div>

            <h2 class="left-title">
                Bienvenue sur<br>
                <span class="highlight">UITS</span> <em>— Espace</em><br>
                <em>d'administration</em>
            </h2>

            <p class="left-desc">
                Plateforme interne de gestion —
                devis, factures, bons de commande, livraison, stock et bien plus.
            </p>

            <div class="features">
                <div class="feat">
                    <div class="feat-icon">📄</div>
                    Factures · Devis · Bons de commande
                </div>
                <div class="feat">
                    <div class="feat-icon">📦</div>
                    Gestion de stock en temps réel
                </div>
                <div class="feat">
                    <div class="feat-icon">📊</div>
                    Tableaux de bord &amp; statistiques
                </div>
                <div class="feat">
                    <div class="feat-icon">🧾</div>
                    Reçus · BL · Attestations
                </div>
            </div>
        </div>

        <div class="left-watermark">UITS — Usage interne uniquement</div>
    </div>

    <!-- ══ RIGHT ══ -->
    <div class="right">
        <div class="form-wrap">

            <div class="brand">
                <div class="brand-logo-wrap">
                    <img src="{{ asset('images/red.png') }}" alt="Logo UITS">
                </div>
                <div class="brand-text">
                    <div class="brand-name">Administration <span>UITS</span></div>
                    <div class="brand-sub">Espace de gestion interne</div>
                </div>
            </div>

            <div class="login-eyebrow">Accès réservé au personnel</div>
            <h1 class="login-h">Connexion</h1>
            <p class="login-sub">Entrez vos identifiants UITS pour accéder à votre espace de travail.</p>

            <div class="security-note">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <p><strong>Accès sécurisé.</strong> Cette plateforme est réservée exclusivement au personnel autorisé de l'UITS.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        Adresse e-mail
                    </label>
                    <input id="email" type="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="prenom.nom@uits.ac.ma"
                        required autocomplete="username">
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="password">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Mot de passe
                    </label>
                    <input id="password" type="password" name="password"
                        placeholder="••••••••"
                        required autocomplete="current-password">
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <label class="remember">
                        <input type="checkbox" name="remember" id="remember_me">
                        Se souvenir de moi
                    </label>
                    @if (Route::has('password.request'))
                        <a class="forgot" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                    @endif
                </div>

                <button type="submit" class="btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Se connecter
                </button>
            </form>

            <div class="copy">© {{ date('Y') }} <strong>UITS</strong> — Usage interne · Tous droits réservés</div>
        </div>
    </div>

</div>
</body>
</html>