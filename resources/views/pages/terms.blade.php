@extends('layouts.app')

@section('title', 'Términos y Condiciones | MORAIA')
@section('meta_description', 'Términos y condiciones de compra y servicio en Moraia.')

@section('content')
<!-- Full-Width Page Hero (Terms) -->
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
            <span class="breadcrumb-current">Términos y Condiciones</span>
        </nav>

        <div class="page-hero-badge">
            <span class="page-badge-dot"></span>
            Términos del Servicio
        </div>

        <h1 class="page-hero-title">Términos y Condiciones</h1>

        <p class="page-hero-desc">
            Condiciones de compra, confirmación de pedidos y servicio de atención en Moraia.
        </p>
    </div>
</section>

<div class="container section-padding" style="padding-top: var(--space-4);">
    <div class="container-narrow" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: var(--space-10);">

        <div style="line-height: 1.8; color: var(--color-text-light); display: flex; flex-direction: column; gap: var(--space-6);">
            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">1. Proceso de Pedidos</h3>
                <p>
                    Al registrar un pedido en nuestro sitio web, este queda reservado temporalmente mientras se concreta la confirmación y el pago a través de nuestro WhatsApp oficial (+58 412 020 6548). Las órdenes no confirmadas en un lapso de 48 horas podrán ser canceladas.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">2. Precios y Disponibilidad</h3>
                <p>
                    Los precios están expresados en Dólares Estadounidenses ($ USD) y se aceptan pagos en divisas o moneda nacional a la tasa oficial acordada al momento del pago. El inventario está sujeto a disponibilidad.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">3. Línea Moraia Íntimo</h3>
                <p>
                    Los productos catalogados bajo la línea <em>Moraia Íntimo</em> están dirigidos exclusivamente a personas mayores de 18 años. Al adquirir estos productos, el cliente declara tener la edad legal requerida.
                </p>
            </div>

            <div>
                <h3 style="font-size: 1.3rem; margin-bottom: var(--space-2); color: var(--color-text);">4. Propiedad Intelectual</h3>
                <p>
                    La marca MORAIA, su isotipo, manual de marca, imágenes y contenidos editoriales son propiedad exclusiva de su fundadora Airan Zambrano.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
