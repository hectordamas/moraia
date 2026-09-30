@extends('layouts.app')

@section('title', 'Política de Privacidad | MORAIA')
@section('meta_description', 'Política de Privacidad y protección de datos personales de Moraia.')

@section('content')
<!-- Full-Width Page Hero (Privacy) -->
<section class="page-hero-section">
    <div class="page-hero-vectors" aria-hidden="true">
        <svg class="cat-vector page-vector-right cat-anim-float-slow" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M40,100 C70,40 140,50 180,20 C160,80 180,140 140,180 C90,160 50,150 40,100 Z" fill="rgba(200, 116, 126, 0.08)"/>
            <circle cx="150" cy="50" r="4" fill="rgba(200, 116, 126, 0.25)" class="cat-anim-pulse"/>
        </svg>
        <div class="page-sparkle page-sparkle-1 cat-anim-sparkle">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.4)"/></svg>
        </div>
    </div>

    <div class="container page-hero-container">
        <nav class="breadcrumbs page-hero-breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Política de Privacidad</span>
        </nav>

        <div class="page-hero-badge">
            <span class="page-badge-dot"></span>
            Seguridad & Transparencia
        </div>

        <h1 class="page-hero-title">Política de Privacidad</h1>

        <p class="page-hero-desc">
            En Moraia cuidamos tus datos personales y privacidad con los más altos estándares de discreción y seguridad.
        </p>
    </div>
</section>

<div class="container section-padding" style="padding-top: var(--space-4);">
    <div class="container-narrow" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: var(--space-10);">

        <div style="line-height: 1.8; color: var(--color-text-light); display: flex; flex-direction: column; gap: var(--space-6);">
            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">1. Información que recopilamos</h3>
                <p>
                    En Moraia recopilamos la información estrictamente necesaria para procesar tus órdenes y brindarte una atención personalizada, tales como: nombre, número de teléfono, WhatsApp, correo electrónico y dirección de entrega.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">2. Uso de la información</h3>
                <p>
                    Tus datos son utilizados exclusivamente para la coordinación logística de tus pedidos, emisión de notas de entrega, atención al cliente y confirmaciones vía WhatsApp. No vendemos ni compartimos tu información personal con terceros.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">3. Confidencialidad y Discreción</h3>
                <p>
                    Para los pedidos correspondientes a nuestra línea <em>Moraia Íntimo</em> y regalos especiales, garantizamos máxima reserva y empaques exteriores totalmente discretos.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">4. Contacto</h3>
                <p>
                    Si tienes preguntas sobre nuestra política de privacidad, puedes comunicarte a través de nuestro correo <strong>By.moraia@gmail.com</strong> o a nuestro WhatsApp oficial <strong>+58 412 020 6548</strong>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
