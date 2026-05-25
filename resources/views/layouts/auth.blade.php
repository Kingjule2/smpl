<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SMPL') - {{ config('app.name', 'SMPL') }}</title>
    <meta name="description" content="@yield('meta_description', 'SMPL - Simple Management Platform')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --primary-glow: rgba(99, 102, 241, 0.15);
            --accent: #a78bfa;
            --bg-dark: #0f0d1a;
            --bg-card: rgba(255, 255, 255, 0.04);
            --bg-card-hover: rgba(255, 255, 255, 0.07);
            --bg-input: rgba(255, 255, 255, 0.06);
            --bg-input-focus: rgba(255, 255, 255, 0.1);
            --border: rgba(255, 255, 255, 0.08);
            --border-focus: rgba(99, 102, 241, 0.5);
            --text-primary: #f1f0f7;
            --text-secondary: #9896a8;
            --text-muted: #6b6980;
            --danger: #ef4444;
            --danger-bg: rgba(239, 68, 68, 0.1);
            --success: #22c55e;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Animated background */
        .bg-gradient {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% -20%, rgba(99, 102, 241, 0.15), transparent),
                radial-gradient(ellipse 60% 50% at 80% 50%, rgba(139, 92, 246, 0.08), transparent),
                radial-gradient(ellipse 60% 50% at 20% 80%, rgba(99, 102, 241, 0.06), transparent);
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black, transparent);
        }

        /* Floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: rgba(99, 102, 241, 0.12);
            top: -10%;
            right: -5%;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 350px;
            height: 350px;
            background: rgba(139, 92, 246, 0.1);
            bottom: -10%;
            left: -5%;
            animation-delay: -7s;
        }

        .orb-3 {
            width: 250px;
            height: 250px;
            background: rgba(167, 139, 250, 0.08);
            top: 40%;
            left: 60%;
            animation-delay: -14s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(20px, 10px) scale(1.02); }
        }

        /* Auth Container */
        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
            padding-top: 40px;
            padding-bottom: 40px;
            margin: auto;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 8px 32px rgba(99, 102, 241, 0.3),
                0 2px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .logo-wrapper::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            border-radius: inherit;
        }

        .logo-wrapper img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            position: relative;
            z-index: 1;
            border-radius: 8px;
        }

        .logo-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--text-primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 6px;
        }

        .logo-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 400;
        }

        /* Card */
        .auth-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.05),
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .auth-card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
            color: var(--text-primary);
        }

        .auth-card-desc {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 28px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 8px;
            letter-spacing: 0.02em;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 18px;
            height: 18px;
            transition: color 0.2s ease;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 44px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-input:hover {
            background: var(--bg-input-focus);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .form-input:focus {
            background: var(--bg-input-focus);
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .form-input:focus ~ .form-input-icon,
        .form-input:focus + .form-input-icon {
            color: var(--primary-light);
        }

        .form-input.is-invalid {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px var(--danger-bg);
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .password-toggle:hover {
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05);
        }

        .password-toggle svg {
            width: 18px;
            height: 18px;
        }

        /* Error Message */
        .form-error {
            font-size: 12px;
            color: var(--danger);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-error svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        /* Checkbox */
        .form-checkbox-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .form-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .form-checkbox {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            border-radius: 4px;
            cursor: pointer;
        }

        .form-link {
            font-size: 13px;
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .form-link:hover {
            color: var(--accent);
        }

        /* Submit Button */
        .btn-primary {
            width: 100%;
            padding: 13px 24px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover::before {
            opacity: 1;
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(99, 102, 241, 0.3);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider-text {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 500;
        }

        /* Footer Link */
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .auth-footer a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-footer a:hover {
            color: var(--accent);
        }

        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: var(--danger-bg);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #86efac;
        }

        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-container {
                padding: 16px;
            }

            .auth-card {
                padding: 24px;
                border-radius: 16px;
            }

            .logo-wrapper {
                width: 60px;
                height: 60px;
                border-radius: 16px;
            }

            .logo-wrapper img {
                width: 40px;
                height: 40px;
            }

            .logo-title {
                font-size: 24px;
            }
        }

        /* Particle animation */
        .particles {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(99, 102, 241, 0.3);
            border-radius: 50%;
            animation: particleFloat linear infinite;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) scale(1);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="bg-gradient"></div>
    <div class="bg-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <!-- Auth Content -->
    <div class="auth-container">
        <!-- Logo -->
        <div class="logo-section">
            <div class="logo-wrapper">
                <img src="{{ asset('images/logo.png') }}" alt="SMPL Logo">
            </div>
            <h1 class="logo-title">SMPL</h1>
            <p class="logo-subtitle">Sistem Monitoring Peternakan Lele</p>
        </div>

        <!-- Card -->
        <div class="auth-card">
            @yield('content')
        </div>

        <!-- Footer -->
        @yield('footer')
    </div>

    <script>
        // Generate floating particles
        (function() {
            const container = document.getElementById('particles');
            const count = 30;

            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDuration = (Math.random() * 15 + 10) + 's';
                particle.style.animationDelay = (Math.random() * 10) + 's';
                particle.style.width = (Math.random() * 3 + 1) + 'px';
                particle.style.height = particle.style.width;
                container.appendChild(particle);
            }
        })();

        // Password toggle functionality
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const eyeOpen = this.querySelector('.eye-open');
                const eyeClosed = this.querySelector('.eye-closed');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeOpen.style.display = 'none';
                    eyeClosed.style.display = 'block';
                } else {
                    input.type = 'password';
                    eyeOpen.style.display = 'block';
                    eyeClosed.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
