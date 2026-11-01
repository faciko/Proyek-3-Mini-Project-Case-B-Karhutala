<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Karhutla Community</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: Arial, Helvetica, sans-serif;
            background: #1F2933;
            color: #263238;
        }
        .card {
            width: 100%;
            max-width: 420px;
            padding: 32px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 18px 50px rgba(0, 0, 0, .3);
        }
        h1 { margin: 0 0 6px; font-size: 26px; color: #1F2933; }
        .subtitle { margin: 0 0 24px; color: #5F6B73; font-size: 14px; }
        label { display: block; margin-bottom: 16px; font-size: 14px; font-weight: bold; }
        input {
            width: 100%;
            margin-top: 6px;
            padding: 11px 12px;
            border: 1px solid #C9D4DA;
            border-radius: 6px;
            font: inherit;
        }
        input:focus { outline: 2px solid #147E7A; outline-offset: 1px; border-color: #147E7A; }
        button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 6px;
            background: #147E7A;
            color: #fff;
            font: inherit;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background: #106965; }
        .alert {
            margin-bottom: 18px;
            padding: 10px 12px;
            border-radius: 6px;
            background: #FDECEC;
            color: #9B1C1C;
            font-size: 13px;
        }
        .error { display: block; margin-top: 6px; color: #B42318; font-size: 12px; font-weight: normal; }
        .hint {
            margin: 22px 0 0;
            padding-top: 16px;
            border-top: 1px solid #E3E9EC;
            color: #5F6B73;
            font-size: 12px;
            line-height: 1.6;
        }
        .hint code { color: #147E7A; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Masuk</h1>
        <p class="subtitle">Aplikasi pelaporan Karhutla</p>

        @if ($errors->any())
            <div class="alert" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <label>
                Email
                <input type="email" name="email" value="{{ old('email') }}"
                       autocomplete="email" autofocus required>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </label>

            <label>
                Password
                <input type="password" name="password"
                       autocomplete="current-password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </label>

            <button type="submit">Login</button>
        </form>

        <p class="hint">
            Akun demo lokal:<br>
            <code>pelapor@example.test</code> &middot; <code>monitor@example.test</code> &middot;
            <code>koordinator@example.test</code><br>
            Password: <code>demo12345</code>
        </p>
    </main>
</body>
</html>
