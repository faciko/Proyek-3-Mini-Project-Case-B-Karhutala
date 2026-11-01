@php
    $currentUser = auth()->user();
    $currentRole = [
        'reporter' => 'Pelapor',
        'monitor' => 'Monitor',
        'coordinator' => 'Koordinator',
    ][$currentUser->role] ?? $currentUser->role;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Karhutla Community')</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: #F3F6F7;
            color: #263238;
        }
        a { color: #147E7A; }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            padding: 14px 28px;
            background: #1F2933;
            color: #fff;
        }
        .brand {
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
            letter-spacing: .02em;
        }
        .brand span { color: #66C2BD; font-weight: normal; }
        .nav { display: flex; gap: 6px; flex-wrap: wrap; }
        .nav a {
            padding: 7px 10px;
            border-radius: 6px;
            color: #D6E4E3;
            font-size: 13px;
            text-decoration: none;
        }
        .nav a:hover, .nav a.active { background: rgba(255, 255, 255, .12); color: #fff; }
        .account { display: flex; align-items: center; gap: 12px; font-size: 12px; color: #C9D6DA; }
        .account form { margin: 0; }
        .logout {
            padding: 7px 12px;
            border: 1px solid rgba(255, 255, 255, .4);
            border-radius: 6px;
            background: transparent;
            color: #fff;
            font: inherit;
            font-size: 12px;
            cursor: pointer;
        }
        .logout:hover { background: rgba(255, 255, 255, .12); }
        .container { max-width: 1080px; margin: 0 auto; padding: 32px 24px 56px; }
        .page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }
        h1 { margin: 0 0 6px; font-size: 26px; line-height: 1.25; color: #1F2933; }
        h2 { margin: 0 0 12px; font-size: 16px; color: #1F2933; }
        .subtitle { margin: 0; color: #5F6B73; font-size: 13px; line-height: 1.5; }
        .back { display: inline-block; margin-bottom: 8px; font-size: 13px; text-decoration: none; }
        .flash {
            margin-bottom: 20px;
            padding: 11px 14px;
            border-left: 4px solid #147E7A;
            border-radius: 6px;
            background: #E7F3F2;
            color: #0F5F5C;
            font-size: 13px;
        }
        .card {
            padding: 22px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(31, 41, 51, .06);
        }
        .btn {
            display: inline-block;
            padding: 10px 16px;
            border: 0;
            border-radius: 6px;
            background: #147E7A;
            color: #fff;
            font: inherit;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }
        .btn:hover { background: #106965; }
        .btn-secondary {
            background: #fff;
            color: #1F2933;
            border: 1px solid #C9D4DA;
        }
        .btn-secondary:hover { background: #F3F6F7; }
        .table-wrap { overflow-x: auto; border-radius: 10px; background: #fff; box-shadow: 0 8px 24px rgba(31, 41, 51, .06); }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th {
            padding: 12px 14px;
            background: #1F2933;
            color: #fff;
            text-align: left;
            font-size: 12px;
            white-space: nowrap;
        }
        td { padding: 12px 14px; border-bottom: 1px solid #E3E9EC; vertical-align: top; }
        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover td { background: #F8FAFB; }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            background: #EEF3F5;
            color: #40525B;
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
        }
        .status-submitted { background: #E8F1FB; color: #245C96; }
        .status-under_review { background: #FFF3D6; color: #8A5A1B; }
        .status-needs_attention { background: #FDE8E4; color: #9B2C1B; }
        .status-ready_to_forward { background: #E7F3F2; color: #0F5F5C; }
        .status-forwarded { background: #EDE9FE; color: #5B3FA3; }
        .status-closed { background: #E8ECEF; color: #45555E; }
        .empty { padding: 40px 24px; text-align: center; color: #5F6B73; }
        .empty p { margin: 0 0 16px; }
        .pager { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 16px; font-size: 13px; }
        .pager a, .pager span.disabled { padding: 7px 11px; border: 1px solid #C9D4DA; border-radius: 6px; text-decoration: none; }
        .pager span.disabled { color: #98A5AB; background: #F8FAFB; }
        .form-card { max-width: 760px; padding: 26px; border-radius: 10px; background: #fff; box-shadow: 0 8px 24px rgba(31, 41, 51, .06); }
        .field { margin-bottom: 17px; }
        .field label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: bold; color: #1F2933; }
        .field input, .field select, .field textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #C9D4DA;
            border-radius: 6px;
            background: #fff;
            color: #263238;
            font: inherit;
            font-size: 14px;
        }
        .field textarea { min-height: 120px; resize: vertical; }
        .field input:focus, .field select:focus, .field textarea:focus {
            outline: 2px solid #147E7A;
            outline-offset: 1px;
            border-color: #147E7A;
        }
        .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 16px; }
        .error { display: block; margin-top: 6px; color: #B42318; font-size: 12px; }
        .hint { margin: 6px 0 0; color: #5F6B73; font-size: 12px; line-height: 1.5; }
        .form-actions { display: flex; align-items: center; gap: 10px; margin-top: 22px; }
        .detail-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.35fr); gap: 18px; }
        .meta { margin: 0; }
        .meta div { display: grid; grid-template-columns: 110px minmax(0, 1fr); gap: 10px; padding: 8px 0; border-bottom: 1px solid #EDF1F3; font-size: 13px; }
        .meta div:last-child { border-bottom: 0; }
        .meta dt { color: #5F6B73; }
        .meta dd { margin: 0; font-weight: bold; color: #1F2933; }
        .description { margin: 0; white-space: pre-line; line-height: 1.7; font-size: 14px; }
        .evidence { margin-top: 15px; padding-top: 15px; border-top: 1px solid #EDF1F3; font-size: 13px; }
        .note-box { margin-top: 15px; padding: 12px 14px; border-radius: 6px; background: #F8FAFB; font-size: 13px; line-height: 1.6; }
        .note-box strong { display: block; margin-bottom: 4px; color: #1F2933; }
        .workflow { max-width: 760px; margin-top: 18px; }
        .workflow .field textarea { min-height: 90px; }
        .area-summary-card { margin-bottom: 18px; }
        .area-summary-field { max-width: 420px; margin-bottom: 0; }
        .summary-output { min-height: 20px; margin: 10px 0 0; color: #147E7A; font-size: 13px; font-weight: bold; }
        .filter-card { margin-bottom: 18px; }
        .filter-grid {
            display: grid;
            grid-template-columns: minmax(190px, 1.4fr) minmax(150px, .9fr) minmax(160px, .9fr) auto;
            gap: 14px;
            align-items: end;
        }
        .filter-actions { display: flex; align-items: center; gap: 8px; }
        .result-count { margin: 0 0 10px; color: #5F6B73; font-size: 13px; }
        .recap-filter { margin-bottom: 18px; }
        .recap-filter-row { display: flex; align-items: end; gap: 14px; flex-wrap: wrap; }
        .recap-filter-row .field { min-width: 240px; margin-bottom: 0; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }
        .stat-card {
            padding: 20px;
            border-radius: 10px;
            background: #fff;
            border-top: 4px solid #147E7A;
            box-shadow: 0 8px 24px rgba(31, 41, 51, .06);
        }
        .stat-card.stat-active { border-top-color: #D9A441; }
        .stat-card.stat-closed { border-top-color: #5B6B75; }
        .stat-label { display: block; color: #5F6B73; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        .stat-value { display: block; margin-top: 8px; color: #1F2933; font-size: 32px; line-height: 1; }
        @media (max-width: 900px) {
            .filter-grid { grid-template-columns: 1fr 1fr; }
            .filter-actions { grid-column: 1 / -1; }
        }
        @media (max-width: 720px) {
            .account span { display: none; }
            .grid-2, .detail-grid { grid-template-columns: 1fr; }
            .filter-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
            h1 { font-size: 22px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}">Karhutla <span>Community</span></a>

        <nav class="nav" aria-label="Navigasi utama">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('reports.index') }}">Laporan</a>
            @if (in_array($currentUser->role, ['monitor', 'coordinator'], true))
                <a href="{{ route('reports.recap') }}">Rekap</a>
            @endif
            @if ($currentUser->role === 'reporter')
                <a href="{{ route('reports.create') }}">Buat laporan</a>
            @endif
        </nav>

        <div class="account">
            <span>{{ $currentUser->name }} &middot; {{ $currentRole }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
    </header>

    <main class="container">
        @if (session('status'))
            <div class="flash" role="status">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
