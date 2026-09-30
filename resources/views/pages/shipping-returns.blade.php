@extends('layouts.app')

@section('title', 'Envíos y Políticas de Devolución | MORAIA')
@section('meta_description', 'Conoce las políticas de entrega en Caracas, envíos nacionales en Venezuela y condiciones de cambio en Moraia.')

@section('content')
<!-- Full-Width Page Hero (Shipping & Returns) -->
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
            <span class="breadcrumb-current">Envíos & Devoluciones</span>
        </nav>

        <div class="page-hero-badge">
            <span class="page-badge-dot"></span>
            Información & Garantía
        </div>

        <h1 class="page-hero-title">Envíos & Políticas de Cambio</h1>

        <p class="page-hero-desc">
            Información transparente sobre entregas en Caracas, despachos nacionales a toda Venezuela y condiciones de cambio.
        </p>
    </div>
</section>

<div class="container section-padding" style="padding-top: var(--space-4);">
    <div class="container-narrow" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: var(--space-10);">

        <div style="line-height: 1.8; color: var(--color-text-light); display: flex; flex-direction: column; gap: var(--space-6);">
            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">🛵 Delivery en Caracas</h3>
                <p>
                    Contamos con servicio de mensajería propia en la Gran Caracas con tarifa fija de <strong>$3.00</strong>. Las entregas se coordinan el mismo día o en un plazo máximo de 24 horas tras confirmar el pedido.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">📦 Envíos Nacionales en Venezuela</h3>
                <p>
                    Realizamos envíos a cualquier estado o ciudad de Venezuela mediante las agencias MRW, Zoom o Tealca con modalidad de cobro en destino (flete pagado por el cliente al retirar). Todos los paquetes viajan debidamente protegidos y sellados.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">🎁 Empaques de Regalo</h3>
                <p>
                    Nuestras cajas de regalo y sets incluyen presentación de lujo con lazo de satén y tarjeta caligrafiada personalizada sin costo adicional en productos de la categoría Regalos.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">🔄 Cambios y Devoluciones</h3>
                <p>
                    Por razones de higiene y salud, no se aceptan cambios ni devoluciones en prendas de uso íntimo (lencería/panties) ni en productos de bienestar íntimo abiertos. En pijamas y batas se admiten cambios de talla dentro de los primeros 3 días posteriores a la recepción, siempre que la prenda conserve sus etiquetas originales y no presente signos de uso.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
