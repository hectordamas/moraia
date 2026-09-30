<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo | MORAIA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body style="background-color: #242020; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="background-color: #FFFFFF; width: 100%; max-width: 420px; border-radius: var(--radius-sm); padding: 40px; box-shadow: var(--shadow-lg);">
        <div class="text-center" style="margin-bottom: 30px;">
            <img src="{{ asset('images/branding/logo_moraia_navbar_oscuro.png') }}" alt="MORAIA" style="height: 48px; margin: 0 auto 10px auto;">
            <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-primary-dark); font-weight: 700;">Acceso Administrativo</span>
        </div>

        @if($errors->any())
            <div style="background-color: var(--color-error-bg); border-left: 4px solid var(--color-error); padding: 12px; border-radius: var(--radius-xs); margin-bottom: 20px; color: var(--color-error); font-size: 0.85rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', 'admin@moraia.com') }}" required autofocus autocomplete="email">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-input" required autocomplete="current-password">
            </div>

            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="remember" value="1" checked>
                    <span style="font-size: var(--text-xs); color: var(--color-text-muted);">Recordar sesión en este equipo</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top: 10px;">
                Ingresar al Panel
            </button>
        </form>

        <div class="text-center" style="margin-top: 25px; padding-top: 15px; border-top: 1px solid var(--color-border-light);">
            <a href="{{ route('home') }}" style="font-size: var(--text-xs); color: var(--color-text-muted);">
                &larr; Volver a la Tienda Pública
            </a>
        </div>
    </div>
</body>
</html>
