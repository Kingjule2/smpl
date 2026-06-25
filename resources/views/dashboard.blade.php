<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Monitoring - SMPL</title>
    <meta name="description" content="Dashboard Monitoring Budidaya Lele - Sistem Monitoring Peternakan Lele">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

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
            --danger-border: rgba(239, 68, 68, 0.3);
            --danger-glow: rgba(239, 68, 68, 0.15);
            --success: #22c55e;
            --success-bg: rgba(34, 197, 94, 0.1);
            --success-border: rgba(34, 197, 94, 0.3);
            --success-glow: rgba(34, 197, 94, 0.15);
            --warning: #f59e0b;
            --warning-bg: rgba(245, 158, 11, 0.1);
            --warning-border: rgba(245, 158, 11, 0.3);
            --blue: #3b82f6;
            --blue-bg: rgba(59, 130, 246, 0.1);
            --blue-border: rgba(59, 130, 246, 0.3);
            --blue-glow: rgba(59, 130, 246, 0.15);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ========== BACKGROUND ========== */
        .bg-gradient-fixed {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% -20%, rgba(99, 102, 241, 0.12), transparent),
                radial-gradient(ellipse 60% 50% at 80% 50%, rgba(139, 92, 246, 0.06), transparent),
                radial-gradient(ellipse 60% 50% at 20% 80%, rgba(99, 102, 241, 0.04), transparent);
            pointer-events: none;
        }

        /* ========== NAVBAR ========== */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 32px;
            background: rgba(15, 13, 26, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .nav-logo img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .nav-title {
            font-size: 18px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--text-primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-user-name {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .nav-user-name strong {
            color: var(--text-primary);
        }

        .btn-logout {
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: var(--danger-bg);
            border-color: var(--danger-border);
            color: #fca5a5;
        }

        /* ========== MAIN CONTENT ========== */
        .dashboard-wrapper {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--text-primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 6px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* ========== FLASH NOTIFICATION ========== */
        .flash-notification {
            padding: 16px 20px;
            border-radius: 14px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.4s ease-out;
            position: relative;
            overflow: hidden;
        }

        .flash-notification::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.05;
            background: linear-gradient(135deg, currentColor, transparent);
        }

        .flash-success {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            color: #86efac;
        }

        .flash-error {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: #fca5a5;
        }

        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 18px;
            opacity: 0.7;
            transition: opacity 0.2s;
            padding: 4px;
        }

        .flash-close:hover {
            opacity: 1;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========== CARDS ========== */
        .card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            transition: all 0.3s ease;
        }

        .card:hover {
            background: var(--bg-card-hover);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .card-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .card-icon-primary {
            background: var(--primary-glow);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .card-icon-success {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
        }

        .card-icon-danger {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
        }

        .card-icon-warning {
            background: var(--warning-bg);
            border: 1px solid var(--warning-border);
        }

        .card-icon-blue {
            background: var(--blue-bg);
            border: 1px solid var(--blue-border);
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
        }

        .card-desc {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        /* ========== LEGEND BAR ========== */
        .legend-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            padding: 18px 24px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .legend-section {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-right: 4px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-secondary);
            padding: 4px 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
        }

        .legend-shape {
            flex-shrink: 0;
        }

        .legend-shape-triangle {
            width: 0;
            height: 0;
            border-left: 7px solid transparent;
            border-right: 7px solid transparent;
            border-bottom: 12px solid var(--text-secondary);
        }

        .legend-shape-circle {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--text-secondary);
        }

        .legend-shape-square {
            width: 14px;
            height: 14px;
            border-radius: 3px;
            background: var(--text-secondary);
        }

        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 4px;
        }

        .legend-color-red { background: #ef4444; }
        .legend-color-green { background: #22c55e; }
        .legend-color-yellow { background: #f59e0b; }
        .legend-color-blue { background: #3b82f6; }

        .legend-divider {
            width: 1px;
            height: 28px;
            background: var(--border);
        }

        /* ========== POND GRID (Cinema seats) ========== */
        .pond-section {
            margin-bottom: 28px;
        }

        .pond-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .pond-section-title {
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pond-section-count {
            font-size: 12px;
            color: var(--text-muted);
            padding: 3px 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
        }

        .pond-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border);
            border-radius: 16px;
            min-height: 80px;
        }

        .pond-grid-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
            font-size: 13px;
            color: var(--text-muted);
            font-style: italic;
        }

        /* ========== POND SHAPES ========== */
        .pond-item {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pond-item:hover {
            transform: scale(1.15);
            z-index: 2;
        }

        .pond-item:hover .pond-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-8px);
        }

        /* Triangle (Bibit) */
        .pond-triangle {
            width: 0;
            height: 0;
            border-left: 28px solid transparent;
            border-right: 28px solid transparent;
            border-bottom: 48px solid currentColor;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));
        }

        .pond-triangle-inner {
            width: 56px;
            height: 52px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        /* Circle (Pembesaran) */
        .pond-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: currentColor;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }

        /* Square (Finishing) */
        .pond-square {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            background: currentColor;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }

        /* Pond Colors */
        .pond-red { color: #ef4444; }
        .pond-green { color: #22c55e; }
        .pond-yellow { color: #f59e0b; }
        .pond-blue { color: #3b82f6; }

        /* Pulse animation for alert (yellow) */
        .pond-yellow .pond-circle,
        .pond-yellow .pond-square,
        .pond-yellow .pond-triangle {
            animation: alertPulse 2s ease-in-out infinite;
        }

        @keyframes alertPulse {
            0%, 100% { filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.3)); }
            50% { filter: drop-shadow(0 0 20px rgba(245, 158, 11, 0.6)); }
        }

        /* Glow for blue (ready) */
        .pond-blue .pond-circle,
        .pond-blue .pond-square,
        .pond-blue .pond-triangle {
            animation: blueGlow 2.5s ease-in-out infinite;
        }

        @keyframes blueGlow {
            0%, 100% { filter: drop-shadow(0 0 6px rgba(59, 130, 246, 0.3)); }
            50% { filter: drop-shadow(0 0 18px rgba(59, 130, 246, 0.6)); }
        }

        /* Pond name label inside shape */
        .pond-label {
            position: absolute;
            font-size: 9px;
            font-weight: 700;
            color: white;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
            pointer-events: none;
            letter-spacing: 0.02em;
            text-align: center;
            max-width: 44px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pond-triangle-inner .pond-label {
            bottom: 10px;
        }

        /* Tooltip */
        .pond-tooltip {
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%) translateY(0);
            background: rgba(15, 13, 26, 0.95);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 16px;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 10;
            pointer-events: none;
        }

        .pond-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: rgba(15, 13, 26, 0.95);
        }

        .tooltip-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .tooltip-row {
            font-size: 11px;
            color: var(--text-secondary);
            margin-bottom: 3px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .tooltip-row strong {
            color: var(--text-primary);
        }

        .tooltip-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 6px;
        }

        .tooltip-status-kosong { background: rgba(239,68,68,0.2); color: #fca5a5; }
        .tooltip-status-terisi { background: rgba(34,197,94,0.2); color: #86efac; }
        .tooltip-status-alert { background: rgba(245,158,11,0.2); color: #fcd34d; }
        .tooltip-status-siap { background: rgba(59,130,246,0.2); color: #93c5fd; }

        /* ========== TOGGLE BUTTON ========== */
        .btn-toggle {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .btn-toggle:hover {
            background: var(--primary-glow);
            border-color: rgba(99, 102, 241, 0.3);
            color: var(--primary-light);
        }

        /* ========== KOLAM FORM (collapsible) ========== */
        .kolam-form-wrapper {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.4s ease, opacity 0.3s ease, margin 0.3s ease;
            margin-bottom: 0;
        }

        .kolam-form-wrapper.active {
            max-height: 700px;
            opacity: 1;
            margin-bottom: 20px;
        }

        .kolam-form-inner {
            padding: 20px;
            background: rgba(99, 102, 241, 0.04);
            border: 1px dashed rgba(99, 102, 241, 0.2);
            border-radius: 16px;
        }

        .kolam-form-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-light);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        /* ========== FORM ========== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            letter-spacing: 0.02em;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239896a8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
        }

        .form-select option {
            background: #1a1830;
            color: var(--text-primary);
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-input:hover,
        .form-select:hover {
            background: var(--bg-input-focus);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .form-input:focus,
        .form-select:focus {
            background: var(--bg-input-focus);
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .form-input.is-invalid,
        .form-select.is-invalid {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px var(--danger-glow);
        }

        .form-error {
            font-size: 12px;
            color: var(--danger);
            margin-top: 6px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .form-unit {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ========== SUBMIT BUTTON ========== */
        .btn-submit {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-submit:hover::before {
            opacity: 1;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit-sm {
            padding: 12px 20px;
            font-size: 14px;
            border-radius: 12px;
        }

        .btn-submit-green {
            background: linear-gradient(135deg, #059669, #10b981);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-submit-green:hover {
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-submit-blue {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-submit-blue:hover {
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        /* ========== MAIN GRID LAYOUT ========== */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        /* ========== STATUS CARD ========== */
        .status-card {
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .status-card::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.03;
            background: linear-gradient(135deg, currentColor, transparent);
        }

        .status-normal {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            box-shadow: 0 0 30px var(--success-glow), inset 0 1px 0 rgba(34, 197, 94, 0.1);
        }

        .status-bahaya {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            box-shadow: 0 0 30px var(--danger-glow), inset 0 1px 0 rgba(239, 68, 68, 0.1);
            animation: dangerPulse 2s ease-in-out infinite;
        }

        @keyframes dangerPulse {
            0%, 100% { box-shadow: 0 0 20px var(--danger-glow); }
            50% { box-shadow: 0 0 40px var(--danger-glow), 0 0 60px rgba(239, 68, 68, 0.08); }
        }

        .status-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        .status-dot-green {
            background: var(--success);
            box-shadow: 0 0 8px var(--success);
        }

        .status-dot-red {
            background: var(--danger);
            box-shadow: 0 0 8px var(--danger);
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .status-title {
            font-size: 15px;
            font-weight: 700;
        }

        .status-normal .status-title { color: #86efac; }
        .status-bahaya .status-title { color: #fca5a5; }

        .status-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 12px;
        }

        .status-info-item {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .status-info-item strong {
            color: var(--text-primary);
        }

        /* ========== REKOMENDASI ========== */
        .rekomendasi-list {
            list-style: none;
            padding: 0;
        }

        .rekomendasi-item {
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 10px;
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }

        .rekomendasi-item:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .rekomendasi-item-danger {
            border-left: 3px solid var(--danger);
            color: #fca5a5;
        }

        .rekomendasi-item-success {
            border-left: 3px solid var(--success);
            color: #86efac;
        }

        .rekomendasi-item-info {
            border-left: 3px solid var(--primary-light);
            color: #c4b5fd;
        }

        /* ========== PARAMETER BADGES ========== */
        .param-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .param-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .param-badge-normal {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            color: #86efac;
        }

        .param-badge-danger {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: #fca5a5;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.6;
        }

        .empty-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-primary);
        }

        .empty-desc {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* ========== CHART SECTION ========== */
        .chart-section {
            margin-top: 0;
        }

        .chart-container {
            position: relative;
            height: 320px;
            padding: 8px;
        }

        /* ========== MODAL ========== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 200;
            align-items: center;
            justify-content: center;
            animation: modalFadeIn 0.25s ease;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: #1a1830;
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 32px;
            max-width: 480px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            animation: modalSlideUp 0.3s ease;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        .modal-close {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: var(--danger-bg);
            border-color: var(--danger-border);
            color: #fca5a5;
        }

        .modal-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .modal-info-item {
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .modal-info-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .modal-info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .modal-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .modal-divider {
            height: 1px;
            background: var(--border);
            margin: 16px 0;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 16px;
            }

            .dashboard-wrapper {
                padding: 20px 16px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-row-2 {
                grid-template-columns: 1fr;
            }

            .form-row-3 {
                grid-template-columns: 1fr;
            }

            .status-info {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 22px;
            }

            .card {
                padding: 20px;
            }

            .legend-bar {
                gap: 10px;
                padding: 14px 16px;
            }

            .legend-divider {
                display: none;
            }

            .pond-grid {
                gap: 8px;
                padding: 14px;
            }

            .modal-info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .nav-user-name {
                display: none;
            }
        }

        /* ========== ANIMATIONS ========== */
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation-delay: 0.05s; animation-fill-mode: both; }
        .stagger-2 { animation-delay: 0.15s; animation-fill-mode: both; }
        .stagger-3 { animation-delay: 0.25s; animation-fill-mode: both; }
        .stagger-4 { animation-delay: 0.35s; animation-fill-mode: both; }
    </style>
</head>
<body>
    {{-- Background --}}
    <div class="bg-gradient-fixed"></div>

    {{-- Navbar --}}
    <nav class="navbar">
        <div class="nav-brand">
            <div class="nav-logo">
                <img src="{{ asset('images/logo.png') }}" alt="SMPL">
            </div>
            <span class="nav-title">SMPL</span>
        </div>
        <div class="nav-user">
            <span class="nav-user-name">Halo, <strong>{{ Auth::user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </nav>

    {{-- Main Dashboard --}}
    <div class="dashboard-wrapper">
        {{-- Page Header --}}
        <div class="page-header fade-in">
            <h1 class="page-title">🐟 Dashboard Monitoring</h1>
            <p class="page-subtitle">Pantau kondisi kolam & analisis budidaya lele secara real-time</p>
        </div>

        {{-- Flash Notification --}}
        @if(session('success'))
            <div class="flash-notification flash-success" id="flash-notification">
                <span>✅</span>
                <span>{{ session('success') }}</span>
                <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="flash-notification flash-error" id="flash-error-msg">
                <span>❌</span>
                <span>{{ session('error') }}</span>
                <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
            </div>
        @endif

        @if($errors->any())
            <div class="flash-notification flash-error" id="flash-error">
                <span>❌</span>
                <span>{{ $errors->first() }}</span>
                <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
            </div>
        @endif

        {{-- ========== LEGEND BAR ========== --}}
        <div class="legend-bar fade-in-up stagger-1">
            <div class="legend-section">
                <span class="legend-section-title">Simbol:</span>
                <div class="legend-item">
                    <div class="legend-shape legend-shape-triangle"></div>
                    <span>Bibit</span>
                </div>
                <div class="legend-item">
                    <div class="legend-shape legend-shape-circle"></div>
                    <span>Pembesaran</span>
                </div>
                <div class="legend-item">
                    <div class="legend-shape legend-shape-square"></div>
                    <span>Finishing</span>
                </div>
            </div>
            <div class="legend-divider"></div>
            <div class="legend-section">
                <span class="legend-section-title">Warna:</span>
                <div class="legend-item">
                    <div class="legend-color legend-color-red"></div>
                    <span>Kosong</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-color-green"></div>
                    <span>Terisi</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-color-yellow"></div>
                    <span>Alert Air</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-color-blue"></div>
                    <span>Siap Pindah</span>
                </div>
            </div>
        </div>

        {{-- ========== VISUAL GRID KOLAM (Cinema Seats) ========== --}}
        <div class="card fade-in-up stagger-2" style="margin-bottom: 24px;">
            <div class="card-header-row">
                <div class="card-header-left">
                    <div class="card-icon card-icon-primary">🏊</div>
                    <div>
                        <h2 class="card-title">Peta Kolam</h2>
                        <p class="card-desc">{{ $kolams->count() }} kolam terdaftar — klik kolam untuk detail</p>
                    </div>
                </div>
                <button type="button" class="btn-toggle" id="btn-toggle-kolam" onclick="toggleKolamForm()">
                    <span>＋</span>
                    <span>Tambah Kolam</span>
                </button>
            </div>

            {{-- Collapsible: Form Tambah Kolam --}}
            <div class="kolam-form-wrapper" id="kolam-form-wrapper">
                <div class="kolam-form-inner">
                    <div class="kolam-form-title">
                        <span>🆕</span> Daftarkan Kolam Baru
                    </div>
                    <form method="POST" action="{{ route('dashboard.storeKolam') }}">
                        @csrf
                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label" for="nama_kolam">Nama Kolam</label>
                                <input type="text" name="nama_kolam" id="nama_kolam"
                                    class="form-input @error('nama_kolam') is-invalid @enderror"
                                    placeholder="Contoh: Kolam A1"
                                    value="{{ old('nama_kolam') }}" required>
                                @error('nama_kolam')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="volume_kolam">Volume (m³)</label>
                                <input type="number" name="volume_kolam" id="volume_kolam"
                                    class="form-input @error('volume_kolam') is-invalid @enderror"
                                    placeholder="5.00"
                                    value="{{ old('volume_kolam') }}"
                                    step="0.01" min="0.1" required>
                                @error('volume_kolam')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label" for="tgl_tebar">Tanggal Tebar</label>
                                <input type="date" name="tgl_tebar" id="tgl_tebar"
                                    class="form-input @error('tgl_tebar') is-invalid @enderror"
                                    value="{{ old('tgl_tebar') }}" required>
                                @error('tgl_tebar')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="jenis_kolam">Jenis Kolam</label>
                                <select name="jenis_kolam" id="jenis_kolam"
                                    class="form-select @error('jenis_kolam') is-invalid @enderror" required>
                                    <option value="">— Pilih Jenis —</option>
                                    <option value="bibit" {{ old('jenis_kolam') == 'bibit' ? 'selected' : '' }}>🔺 Kolam Bibit</option>
                                    <option value="pembesaran" {{ old('jenis_kolam') == 'pembesaran' ? 'selected' : '' }}>⭕ Kolam Pembesaran</option>
                                    <option value="finishing" {{ old('jenis_kolam') == 'finishing' ? 'selected' : '' }}>⬛ Kolam Finishing</option>
                                </select>
                                @error('jenis_kolam')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="metode_budidaya_kolam">Metode Budidaya</label>
                                <select name="metode_budidaya" id="metode_budidaya_kolam"
                                    class="form-select @error('metode_budidaya') is-invalid @enderror" required>
                                    <option value="">— Pilih —</option>
                                    <option value="bioflok" {{ old('metode_budidaya') == 'bioflok' ? 'selected' : '' }}>🧪 Bioflok</option>
                                    <option value="konvensional" {{ old('metode_budidaya') == 'konvensional' ? 'selected' : '' }}>🏗️ Konvensional</option>
                                </select>
                                @error('metode_budidaya')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="status_awal">Status Awal Kolam</label>
                            <select name="status_awal" id="status_awal"
                                class="form-select @error('status_awal') is-invalid @enderror" required>
                                <option value="kosong" {{ old('status_awal', 'kosong') == 'kosong' ? 'selected' : '' }}>🔴 Kosong (belum diisi ikan)</option>
                                <option value="terisi" {{ old('status_awal') == 'terisi' ? 'selected' : '' }}>🟢 Terisi (sudah ada ikan)</option>
                            </select>
                            @error('status_awal')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn-submit btn-submit-sm btn-submit-green">
                            <span>💾</span>
                            <span>Daftarkan Kolam</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- ====== GRID SECTIONS ====== --}}

            {{-- Kolam Bibit --}}
            <div class="pond-section">
                <div class="pond-section-header">
                    <div class="pond-section-title">
                        🔺 Kolam Bibit
                        <span class="pond-section-count">{{ $kolamBibit->count() }} kolam</span>
                    </div>
                    <span style="font-size: 11px; color: var(--text-muted);">Batas: 20 hari → siap sortir</span>
                </div>
                <div class="pond-grid">
                    @forelse($kolamBibit as $k)
                        <div class="pond-item {{ $k->color }}" onclick="openModal({{ $k->id }})" data-kolam-id="{{ $k->id }}">
                            <div class="pond-triangle-inner">
                                <div class="pond-triangle"></div>
                                <span class="pond-label">{{ Str::limit($k->nama_kolam, 6) }}</span>
                            </div>
                            {{-- Tooltip --}}
                            <div class="pond-tooltip">
                                <div class="tooltip-title">{{ $k->nama_kolam }}</div>
                                <div class="tooltip-row"><span>Jenis</span> <strong>{{ $k->jenis_label }}</strong></div>
                                <div class="tooltip-row"><span>Volume</span> <strong>{{ $k->volume_kolam }} m³</strong></div>
                                <div class="tooltip-row"><span>Umur</span> <strong>{{ $k->umur_hari }} hari</strong></div>
                                <div class="tooltip-row"><span>Batas</span> <strong>{{ $k->batas_hari }} hari</strong></div>
                                <span class="tooltip-status tooltip-status-{{ $k->auto_status === 'siap_pindah' ? 'siap' : $k->auto_status }}">
                                    {{ $k->status_label }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="pond-grid-empty">Belum ada kolam bibit</div>
                    @endforelse
                </div>
            </div>

            {{-- Kolam Pembesaran --}}
            <div class="pond-section">
                <div class="pond-section-header">
                    <div class="pond-section-title">
                        ⭕ Kolam Pembesaran
                        <span class="pond-section-count">{{ $kolamPembesaran->count() }} kolam</span>
                    </div>
                    <span style="font-size: 11px; color: var(--text-muted);">Batas: 40 hari → siap pindah finishing</span>
                </div>
                <div class="pond-grid">
                    @forelse($kolamPembesaran as $k)
                        <div class="pond-item {{ $k->color }}" onclick="openModal({{ $k->id }})" data-kolam-id="{{ $k->id }}">
                            <div class="pond-circle"></div>
                            <span class="pond-label">{{ Str::limit($k->nama_kolam, 6) }}</span>
                            <div class="pond-tooltip">
                                <div class="tooltip-title">{{ $k->nama_kolam }}</div>
                                <div class="tooltip-row"><span>Jenis</span> <strong>{{ $k->jenis_label }}</strong></div>
                                <div class="tooltip-row"><span>Volume</span> <strong>{{ $k->volume_kolam }} m³</strong></div>
                                <div class="tooltip-row"><span>Umur</span> <strong>{{ $k->umur_hari }} hari</strong></div>
                                <div class="tooltip-row"><span>Batas</span> <strong>{{ $k->batas_hari }} hari</strong></div>
                                <span class="tooltip-status tooltip-status-{{ $k->auto_status === 'siap_pindah' ? 'siap' : $k->auto_status }}">
                                    {{ $k->status_label }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="pond-grid-empty">Belum ada kolam pembesaran</div>
                    @endforelse
                </div>
            </div>

            {{-- Kolam Finishing --}}
            <div class="pond-section">
                <div class="pond-section-header">
                    <div class="pond-section-title">
                        ⬛ Kolam Finishing
                        <span class="pond-section-count">{{ $kolamFinishing->count() }} kolam</span>
                    </div>
                    <span style="font-size: 11px; color: var(--text-muted);">Batas: 80-100 hari → siap panen</span>
                </div>
                <div class="pond-grid">
                    @forelse($kolamFinishing as $k)
                        <div class="pond-item {{ $k->color }}" onclick="openModal({{ $k->id }})" data-kolam-id="{{ $k->id }}">
                            <div class="pond-square"></div>
                            <span class="pond-label">{{ Str::limit($k->nama_kolam, 6) }}</span>
                            <div class="pond-tooltip">
                                <div class="tooltip-title">{{ $k->nama_kolam }}</div>
                                <div class="tooltip-row"><span>Jenis</span> <strong>{{ $k->jenis_label }}</strong></div>
                                <div class="tooltip-row"><span>Volume</span> <strong>{{ $k->volume_kolam }} m³</strong></div>
                                <div class="tooltip-row"><span>Umur</span> <strong>{{ $k->umur_hari }} hari</strong></div>
                                <div class="tooltip-row"><span>Batas</span> <strong>{{ $k->batas_hari }} hari</strong></div>
                                <span class="tooltip-status tooltip-status-{{ $k->auto_status === 'siap_pindah' ? 'siap' : $k->auto_status }}">
                                    {{ $k->status_label }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="pond-grid-empty">Belum ada kolam finishing</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ========== MAIN GRID: Form Monitoring + Hasil Analisis ========== --}}
        <div class="main-grid">
            {{-- LEFT COLUMN --}}
            <div style="display: flex; flex-direction: column; gap: 24px;">

                {{-- CARD: Form Monitoring --}}
                @if($kolams->where('status_kolam', 'terisi')->count() > 0)
                <div class="card fade-in-up stagger-3">
                    <div class="card-header">
                        <div class="card-icon card-icon-primary">📋</div>
                        <div>
                            <h2 class="card-title">Cek Parameter Air</h2>
                            <p class="card-desc">Input rutin harian — pilih kolam lalu isi parameter</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('dashboard.store') }}" id="monitoring-form">
                        @csrf

                        {{-- Pilih Kolam --}}
                        <div class="form-group">
                            <label class="form-label" for="kolam_id">Pilih Kolam</label>
                            <select name="kolam_id" id="kolam_id" class="form-select @error('kolam_id') is-invalid @enderror" required>
                                <option value="">— Pilih Kolam —</option>
                                @foreach($kolams->where('status_kolam', 'terisi') as $kolam)
                                    <option value="{{ $kolam->id }}" {{ old('kolam_id') == $kolam->id ? 'selected' : '' }}>
                                        {{ $kolam->nama_kolam }} — {{ $kolam->jenis_label }} · {{ $kolam->volume_kolam }} m³
                                    </option>
                                @endforeach
                            </select>
                            @error('kolam_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Parameter Air --}}
                        <div class="form-group">
                            <label class="form-label">Parameter Air</label>
                            <div class="form-row">
                                <div>
                                    <input type="number" name="suhu" id="suhu"
                                        class="form-input @error('suhu') is-invalid @enderror"
                                        placeholder="27.5"
                                        value="{{ old('suhu') }}"
                                        step="0.01" min="0" max="50" required>
                                    <div class="form-unit">🌡️ Suhu (°C)</div>
                                    @error('suhu')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" name="ph" id="ph"
                                        class="form-input @error('ph') is-invalid @enderror"
                                        placeholder="7.5"
                                        value="{{ old('ph') }}"
                                        step="0.01" min="0" max="14" required>
                                    <div class="form-unit">💧 pH Air</div>
                                    @error('ph')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" name="amonia" id="amonia"
                                        class="form-input @error('amonia') is-invalid @enderror"
                                        placeholder="0.25"
                                        value="{{ old('amonia') }}"
                                        step="0.0001" min="0" max="100" required>
                                    <div class="form-unit">⚗️ Amonia (ppm)</div>
                                    @error('amonia')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn-submit">
                            <span>📊</span>
                            <span>Simpan & Analisis</span>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- RIGHT COLUMN: Hasil Analisis --}}
            <div class="card fade-in-up stagger-3">
                <div class="card-header">
                    <div class="card-icon {{ session('analisis') ? (session('analisis.kondisi_air') === 'bahaya' ? 'card-icon-danger' : 'card-icon-success') : 'card-icon-primary' }}">
                        {{ session('analisis') ? (session('analisis.kondisi_air') === 'bahaya' ? '🚨' : '✅') : '📊' }}
                    </div>
                    <div>
                        <h2 class="card-title">Hasil Analisis</h2>
                        <p class="card-desc">
                            @if(session('analisis'))
                                {{ session('analisis.kolam_nama') }} · {{ session('analisis.metode') }}
                            @else
                                Kirim data monitoring untuk melihat hasil
                            @endif
                        </p>
                    </div>
                </div>

                @if(session('analisis'))
                    @php $analisis = session('analisis'); @endphp

                    {{-- Status Card --}}
                    <div class="status-card {{ $analisis['kondisi_air'] === 'bahaya' ? 'status-bahaya' : 'status-normal' }}">
                        <div class="status-header">
                            <div class="status-dot {{ $analisis['kondisi_air'] === 'bahaya' ? 'status-dot-red' : 'status-dot-green' }}"></div>
                            <span class="status-title">
                                Status: {{ $analisis['kondisi_air'] === 'bahaya' ? 'BAHAYA' : 'NORMAL' }}
                            </span>
                        </div>

                        <div class="status-info">
                            <div class="status-info-item">
                                Panen: <strong>{{ $analisis['status_panen'] }}</strong>
                            </div>
                            <div class="status-info-item">
                                Umur: <strong>{{ $analisis['umur_ikan'] }} hari</strong>
                            </div>
                            <div class="status-info-item">
                                Metode: <strong>{{ $analisis['metode'] }}</strong>
                            </div>
                            <div class="status-info-item">
                                @if($analisis['status_panen'] === 'Siap Panen')
                                    <strong style="color: var(--success);">🎉 Siap Panen!</strong>
                                @else
                                    Sisa: <strong>{{ (int) $analisis['sisa_hari'] }} hari</strong>
                                @endif
                            </div>
                        </div>

                        {{-- Parameter Badges --}}
                        <div class="param-badges">
                            <span class="param-badge {{ $analisis['suhu'] >= 25 && $analisis['suhu'] <= 30 ? 'param-badge-normal' : 'param-badge-danger' }}">
                                🌡️ {{ $analisis['suhu'] }}°C
                            </span>
                            <span class="param-badge {{ $analisis['ph'] >= 7 ? 'param-badge-normal' : 'param-badge-danger' }}">
                                💧 pH {{ $analisis['ph'] }}
                            </span>
                            <span class="param-badge {{ $analisis['amonia'] <= 0.5 ? 'param-badge-normal' : 'param-badge-danger' }}">
                                ⚗️ {{ $analisis['amonia'] }} ppm
                            </span>
                        </div>
                    </div>

                    {{-- Rekomendasi --}}
                    <div style="margin-top: 4px;">
                        <label class="form-label" style="margin-bottom: 12px;">Rekomendasi Tindakan</label>
                        <ul class="rekomendasi-list">
                            @foreach($analisis['rekomendasi'] as $rek)
                                @php
                                    $rekClass = 'rekomendasi-item-info';
                                    if (str_contains($rek, '⚠️') || str_contains($rek, '🌡️')) {
                                        $rekClass = 'rekomendasi-item-danger';
                                    } elseif (str_contains($rek, '✅') || str_contains($rek, '🎉')) {
                                        $rekClass = 'rekomendasi-item-success';
                                    }
                                @endphp
                                <li class="rekomendasi-item {{ $rekClass }}">{{ $rek }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif($latestLog)
                    {{-- Show latest log if no fresh analysis --}}
                    <div class="status-card {{ $latestLog->kondisi_air === 'bahaya' ? 'status-bahaya' : 'status-normal' }}">
                        <div class="status-header">
                            <div class="status-dot {{ $latestLog->kondisi_air === 'bahaya' ? 'status-dot-red' : 'status-dot-green' }}"></div>
                            <span class="status-title">
                                Terakhir: {{ $latestLog->kondisi_air === 'bahaya' ? 'BAHAYA' : 'NORMAL' }}
                            </span>
                        </div>
                        <div class="status-info">
                            <div class="status-info-item">
                                Kolam: <strong>{{ $latestLog->kolam->nama_kolam }}</strong>
                            </div>
                            <div class="status-info-item">
                                Waktu: <strong>{{ $latestLog->created_at->diffForHumans() }}</strong>
                            </div>
                            <div class="status-info-item">
                                Panen: <strong>{{ $latestLog->status_panen }}</strong>
                            </div>
                            <div class="status-info-item">
                                Umur: <strong>{{ (int) $latestLog->umur_ikan }} hari</strong>
                            </div>
                        </div>
                        <div class="param-badges">
                            <span class="param-badge {{ $latestLog->suhu >= 25 && $latestLog->suhu <= 30 ? 'param-badge-normal' : 'param-badge-danger' }}">
                                🌡️ {{ $latestLog->suhu }}°C
                            </span>
                            <span class="param-badge {{ $latestLog->ph >= 7 ? 'param-badge-normal' : 'param-badge-danger' }}">
                                💧 pH {{ $latestLog->ph }}
                            </span>
                            <span class="param-badge {{ $latestLog->amonia <= 0.5 ? 'param-badge-normal' : 'param-badge-danger' }}">
                                ⚗️ {{ $latestLog->amonia }} ppm
                            </span>
                        </div>
                    </div>

                    @if($latestLog->rekomendasi)
                        <div style="margin-top: 4px;">
                            <label class="form-label" style="margin-bottom: 12px;">Rekomendasi Terakhir</label>
                            <ul class="rekomendasi-list">
                                @foreach(explode("\n", $latestLog->rekomendasi) as $rek)
                                    @php
                                        $rekClass = 'rekomendasi-item-info';
                                        if (str_contains($rek, '⚠️') || str_contains($rek, '🌡️')) {
                                            $rekClass = 'rekomendasi-item-danger';
                                        } elseif (str_contains($rek, '✅') || str_contains($rek, '🎉')) {
                                            $rekClass = 'rekomendasi-item-success';
                                        }
                                    @endphp
                                    <li class="rekomendasi-item {{ $rekClass }}">{{ $rek }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-icon">📊</div>
                        <h3 class="empty-title">Belum Ada Data</h3>
                        <p class="empty-desc">
                            @if($kolams->count() > 0)
                                Isi form "Cek Parameter Air" di sebelah kiri untuk memulai analisis.
                            @else
                                Daftarkan kolam terlebih dahulu, lalu mulai monitoring parameter air.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- CHART SECTION --}}
        <div class="card fade-in-up stagger-4 chart-section">
            <div class="card-header">
                <div class="card-icon card-icon-primary">📈</div>
                <div>
                    <h2 class="card-title">Tren Parameter Air</h2>
                    <p class="card-desc">Grafik pH dan Amonia dari data monitoring</p>
                </div>
            </div>

            @if($logs->count() > 0)
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📉</div>
                    <h3 class="empty-title">Belum Ada Data Tren</h3>
                    <p class="empty-desc">Grafik akan muncul setelah Anda menginput data monitoring.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ========== MODAL DETAIL KOLAM ========== --}}
    <div class="modal-overlay" id="kolam-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-kolam-nama"></h3>
                <button class="modal-close" onclick="closeModal()">✕</button>
            </div>

            <div class="modal-info-grid">
                <div class="modal-info-item">
                    <div class="modal-info-label">Jenis</div>
                    <div class="modal-info-value" id="modal-kolam-jenis"></div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-label">Volume</div>
                    <div class="modal-info-value" id="modal-kolam-volume"></div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-label">Umur Ikan</div>
                    <div class="modal-info-value" id="modal-kolam-umur"></div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-label">Batas Hari</div>
                    <div class="modal-info-value" id="modal-kolam-batas"></div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-label">Status</div>
                    <div class="modal-info-value" id="modal-kolam-status"></div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-label">Metode</div>
                    <div class="modal-info-value" id="modal-kolam-metode"></div>
                </div>
            </div>

            {{-- Actions: Move/Harvest --}}
            <div class="modal-actions" id="modal-actions" style="display: none;">
                <div class="modal-divider"></div>

                {{-- Move Form --}}
                <div id="modal-move-form" style="display: none;">
                    <label class="form-label" style="margin-bottom: 8px;">Pilih Kolam Tujuan (status kosong)</label>
                    <form method="POST" id="move-form">
                        @csrf
                        <select name="kolam_tujuan_id" class="form-select" style="margin-bottom: 12px;" id="modal-kolam-tujuan" required>
                            <option value="">— Pilih Kolam Tujuan —</option>
                        </select>
                        <button type="submit" class="btn-submit btn-submit-sm btn-submit-blue">
                            <span>🔄</span>
                            <span>Konfirmasi Pindah</span>
                        </button>
                    </form>
                </div>

                {{-- Harvest Form --}}
                <div id="modal-harvest-form" style="display: none;">
                    <form method="POST" id="harvest-form">
                        @csrf
                        <button type="submit" class="btn-submit btn-submit-sm btn-submit-green">
                            <span>🎉</span>
                            <span>Konfirmasi Panen</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== SCRIPTS ========== --}}
    <script>
        // Kolam data for modals
        const kolamsData = {!! $kolams->map(fn($k) => [
            'id' => $k->id,
            'nama_kolam' => $k->nama_kolam,
            'jenis_kolam' => $k->jenis_kolam,
            'jenis_label' => $k->jenis_label,
            'volume_kolam' => $k->volume_kolam,
            'umur_hari' => $k->umur_hari,
            'batas_hari' => $k->batas_hari,
            'auto_status' => $k->auto_status,
            'status_label' => $k->status_label,
            'status_kolam' => $k->status_kolam,
            'metode_budidaya' => ucfirst($k->metode_budidaya),
        ])->keyBy('id')->toJson() !!};

        // Next stage mapping
        const nextStage = {
            'bibit': 'pembesaran',
            'pembesaran': 'finishing',
        };

        // Toggle Kolam Form
        function toggleKolamForm() {
            const wrapper = document.getElementById('kolam-form-wrapper');
            const btn = document.getElementById('btn-toggle-kolam');
            wrapper.classList.toggle('active');

            if (wrapper.classList.contains('active')) {
                btn.innerHTML = '<span>✕</span><span>Batal</span>';
                btn.style.background = 'var(--danger-bg)';
                btn.style.borderColor = 'var(--danger-border)';
                btn.style.color = '#fca5a5';
            } else {
                btn.innerHTML = '<span>＋</span><span>Tambah Kolam</span>';
                btn.style.background = '';
                btn.style.borderColor = '';
                btn.style.color = '';
            }
        }

        // Open Modal
        function openModal(kolamId) {
            const kolam = kolamsData[kolamId];
            if (!kolam) return;

            document.getElementById('modal-kolam-nama').textContent = kolam.nama_kolam;
            document.getElementById('modal-kolam-jenis').textContent = kolam.jenis_label;
            document.getElementById('modal-kolam-volume').textContent = kolam.volume_kolam + ' m³';
            document.getElementById('modal-kolam-umur').textContent = kolam.umur_hari + ' hari';
            document.getElementById('modal-kolam-batas').textContent = kolam.batas_hari + ' hari';
            document.getElementById('modal-kolam-status').textContent = kolam.status_label;
            document.getElementById('modal-kolam-metode').textContent = kolam.metode_budidaya;

            const actionsDiv = document.getElementById('modal-actions');
            const moveForm = document.getElementById('modal-move-form');
            const harvestForm = document.getElementById('modal-harvest-form');

            moveForm.style.display = 'none';
            harvestForm.style.display = 'none';
            actionsDiv.style.display = 'none';

            // Show action buttons if status is siap_pindah
            if (kolam.auto_status === 'siap_pindah') {
                actionsDiv.style.display = 'block';

                if (kolam.jenis_kolam === 'finishing') {
                    // Show harvest button
                    harvestForm.style.display = 'block';
                    document.getElementById('harvest-form').action = '/dashboard/kolam/' + kolamId + '/harvest';
                } else {
                    // Show move form with target ponds
                    moveForm.style.display = 'block';
                    document.getElementById('move-form').action = '/dashboard/kolam/' + kolamId + '/move';

                    // Populate target kolam dropdown
                    const targetJenis = nextStage[kolam.jenis_kolam];
                    const select = document.getElementById('modal-kolam-tujuan');
                    select.innerHTML = '<option value="">— Pilih Kolam Tujuan —</option>';

                    Object.values(kolamsData).forEach(k => {
                        if (k.jenis_kolam === targetJenis && k.status_kolam === 'kosong') {
                            const opt = document.createElement('option');
                            opt.value = k.id;
                            opt.textContent = k.nama_kolam + ' — ' + k.volume_kolam + ' m³';
                            select.appendChild(opt);
                        }
                    });
                }
            }

            document.getElementById('kolam-modal').classList.add('active');
        }

        // Close Modal
        function closeModal() {
            document.getElementById('kolam-modal').classList.remove('active');
        }

        // Close modal on overlay click
        document.getElementById('kolam-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close modal on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // Auto-open kolam form if there are validation errors for kolam fields
        @if($errors->has('nama_kolam') || $errors->has('volume_kolam') || $errors->has('tgl_tebar') || $errors->has('metode_budidaya') || $errors->has('jenis_kolam') || $errors->has('status_awal'))
            document.addEventListener('DOMContentLoaded', function() {
                toggleKolamForm();
            });
        @endif

        // Auto-dismiss flash notification
        (function() {
            const flash = document.getElementById('flash-notification');
            if (flash) {
                setTimeout(() => {
                    flash.style.transition = 'all 0.4s ease';
                    flash.style.opacity = '0';
                    flash.style.transform = 'translateY(-12px)';
                    setTimeout(() => flash.remove(), 400);
                }, 6000);
            }
        })();

        // Chart.js - Tren pH & Amonia
        @if($logs->count() > 0)
        (function() {
            const ctx = document.getElementById('trendChart').getContext('2d');

            const labels = {!! $logs->map(fn($l) => $l->created_at->format('d/m H:i'))->toJson() !!};
            const phData = {!! $logs->pluck('ph')->map(fn($v) => (float) $v)->toJson() !!};
            const amoniaData = {!! $logs->pluck('amonia')->map(fn($v) => (float) $v)->toJson() !!};
            const suhuData = {!! $logs->pluck('suhu')->map(fn($v) => (float) $v)->toJson() !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'pH Air',
                            data: phData,
                            borderColor: '#a78bfa',
                            backgroundColor: 'rgba(167, 139, 250, 0.1)',
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#a78bfa',
                            pointBorderColor: '#0f0d1a',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Amonia (ppm)',
                            data: amoniaData,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#0f0d1a',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            yAxisID: 'y1',
                        },
                        {
                            label: 'Suhu (°C)',
                            data: suhuData,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.05)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.4,
                            fill: false,
                            pointRadius: 3,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#0f0d1a',
                            pointBorderWidth: 2,
                            pointHoverRadius: 5,
                            yAxisID: 'y',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#9896a8',
                                font: { family: 'Inter', size: 12 },
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 13, 26, 0.95)',
                            titleColor: '#f1f0f7',
                            bodyColor: '#9896a8',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 12,
                            titleFont: { family: 'Inter', weight: '600' },
                            bodyFont: { family: 'Inter' },
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.04)',
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#6b6980',
                                font: { family: 'Inter', size: 11 },
                                maxRotation: 45,
                            }
                        },
                        y: {
                            position: 'left',
                            grid: {
                                color: 'rgba(255,255,255,0.04)',
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#6b6980',
                                font: { family: 'Inter', size: 11 },
                            },
                            title: {
                                display: true,
                                text: 'pH / Suhu',
                                color: '#6b6980',
                                font: { family: 'Inter', size: 12 },
                            }
                        },
                        y1: {
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                color: '#6b6980',
                                font: { family: 'Inter', size: 11 },
                            },
                            title: {
                                display: true,
                                text: 'Amonia (ppm)',
                                color: '#6b6980',
                                font: { family: 'Inter', size: 12 },
                            }
                        }
                    }
                }
            });
        })();
        @endif
    </script>
</body>
</html>
