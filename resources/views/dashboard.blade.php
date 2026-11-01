@php
    $user = auth()->user();
    $roleLabel = [
        'reporter' => 'Pelapor',
        'monitor' => 'Monitor',
        'coordinator' => 'Koordinator',
    ][$user->role] ?? $user->role;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Karhutla Community</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: #F3F6F7;
            color: #263238;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 28px;
            background: #1F2933;
            color: #fff;
        }
        header strong { font-size: 15px; }
        header form { margin: 0; }
        .logout {
            padding: 8px 14px;
            border: 1px solid rgba(255, 255, 255, .45);
            border-radius: 6px;
            background: transparent;
            color: #fff;
            font: inherit;
            font-size: 13px;
            cursor: pointer;
        }
        .logout:hover { background: rgba(255, 255, 255, .12); }
        main { max-width: 900px; margin: 0 auto; padding: 40px 24px; }
        .welcome {
            padding: 28px;
            border-radius: 10px;
            background: #fff;
            border-left: 5px solid #147E7A;
            box-shadow: 0 8px 24px rgba(31, 41, 51, .08);
        }
        h1 { margin: 0 0 8px; font-size: 26px; color: #1F2933; }
        p { margin: 0; color: #5F6B73; line-height: 1.6; }
        .badge {
            display: inline-block;
            margin-top: 14px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #E7F3F2;
            color: #147E7A;
            font-size: 12px;
            font-weight: bold;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(31, 41, 51, .06);
        }
        .card h2 { margin: 0 0 8px; font-size: 15px; color: #1F2933; }
        .card p { font-size: 13px; }
        .card a { display: inline-block; margin-top: 12px; color: #147E7A; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>
    <header>
        <strong>Karhutla Community</strong>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout">Logout</button>
        </form>
    </header>

    <main>
        <section class="welcome">
            <h1>Halo, {{ $user->name }}</h1>
            <p>Kamu masuk sebagai {{ $roleLabel }}. Halaman ini dilindungi middleware <code>auth</code>, jadi hanya bisa dibuka setelah login.</p>
            <span class="badge">Role: {{ $user->role }}</span>
        </section>

        <div class="grid">
            @if ($user->role === 'reporter')
                <article class="card">
                    <h2>Laporan saya</h2>
                    <p>Buat laporan indikasi kebakaran dan pantau status laporan milikmu.</p>
                    <a href="{{ route('reports.index') }}">Lihat laporan saya &rarr;</a>
                </article>
                <article class="card">
                    <h2>Hak akses</h2>
                    <p>Pelapor hanya bisa melihat dan mengubah laporan miliknya sendiri saat status masih <strong>submitted</strong>.</p>
                    <a href="{{ route('reports.create') }}">Buat laporan baru &rarr;</a>
                </article>
            @else
                <article class="card">
                    <h2>Tinjau laporan</h2>
                    <p>Relawan dan koordinator dapat melihat laporan dari semua pelapor.</p>
                    <a href="{{ route('reports.index') }}">Lihat semua laporan &rarr;</a>
                </article>
                <article class="card">
                    <h2>Rekap laporan</h2>
                    <p>Lihat total laporan, laporan aktif, laporan selesai, dan tabel per wilayah.</p>
                    <a href="{{ route('reports.recap') }}">Buka rekap &rarr;</a>
                </article>
                <article class="card">
                    <h2>Hak akses</h2>
                    <p>Halaman review dijaga middleware <code>role:monitor,coordinator</code>, sehingga akun pelapor akan mendapat respons 403.</p>
                </article>
            @endif
        </div>
    </main>
</body>
</html>
