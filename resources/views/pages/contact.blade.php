@extends('layouts.app')

@section('title', 'Contacto & Atención Personalizada | MORAIA')
@section('meta_description', 'Contáctanos directamente por WhatsApp o déjanos tu mensaje. Estamos para asesorarte en tus regalos y compras.')

@section('content')
<!-- Full-Width Page Hero (Contact) -->
<section class="page-hero-section">
    <div class="page-hero-vectors" aria-hidden="true">
        <svg class="cat-vector page-vector-right cat-anim-float-slow" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M40,100 C70,40 140,50 180,20 C160,80 180,140 140,180 C90,160 50,150 40,100 Z" fill="rgba(200, 116, 126, 0.08)"/>
            <circle cx="150" cy="50" r="4" fill="rgba(200, 116, 126, 0.25)" class="cat-anim-pulse"/>
        </svg>
        <div class="page-sparkle page-sparkle-1 cat-anim-sparkle">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.4)"/></svg>
        </div>
        <div class="page-sparkle page-sparkle-2 cat-anim-sparkle" style="animation-delay: 2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.3)"/></svg>
        </div>
    </div>

    <div class="container page-hero-container">
        <nav class="breadcrumbs page-hero-breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Contacto</span>
        </nav>

        <div class="page-hero-badge">
            <span class="page-badge-dot"></span>
            Estamos para Consentirte
        </div>

        <h1 class="page-hero-title">Contacto & Asesoría</h1>

        <p class="page-hero-desc">
            ¿Tienes dudas sobre una talla, un producto o deseas una caja de regalo personalizada? Escríbenos directamente o déjanos un mensaje.
        </p>
    </div>
</section>

<div class="container section-padding" style="padding-top: var(--space-4);">

    <div class="grid-contact">
        <!-- Contact Form -->
        <div class="checkout-card">
            <h2 class="checkout-card-title">Envíanos un Mensaje</h2>

            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="grid-form-2">
                    <div class="form-group">
                        <label class="form-label" for="name">Nombre Completo *</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Correo Electrónico *</label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="grid-form-2">
                    <div class="form-group">
                        <label class="form-label" for="phone">Teléfono / WhatsApp</label>
                        <input type="tel" id="phone" name="phone" class="form-input" placeholder="Ej: 04121234567" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="subject">Asunto *</label>
                        <input type="text" id="subject" name="subject" class="form-input" placeholder="Consulta sobre producto / Pedido" value="{{ old('subject') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">Mensaje *</label>
                    <textarea id="message" name="message" class="form-textarea" rows="5" placeholder="Cuéntanos en qué podemos ayudarte..." required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Enviar Mensaje
                </button>
            </form>
        </div>

        <!-- Contact Channels & Info -->
        <div>
            <!-- WhatsApp Priority Box -->
            <div style="background-color: #FAF5F2; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: var(--space-8); margin-bottom: var(--space-6);">
                <span class="badge" style="background-color: #25D366; color: #FFFFFF; margin-bottom: 10px;">Respuesta Inmediata</span>
                <h3 style="font-family: var(--font-display); font-size: 1.6rem; margin-bottom: 8px;">Atención por WhatsApp</h3>
                <p style="font-size: var(--text-sm); color: var(--color-text-muted); line-height: 1.6; margin-bottom: 18px;">
                    Para una atención rápida, asesoría de tallas y confirmación de pedidos, nuestro canal de WhatsApp está activo.
                </p>
                <a href="https://wa.me/584120206548?text=Hola%20Moraia%2C%20quisiera%20recibir%20asesor%C3%ADa%20personalizada." 
                   target="_blank" 
                   rel="noopener" 
                   class="btn btn-lg btn-block" 
                   style="background-color: #25D366; color: #FFFFFF; border: 1px solid #25D366; box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    Chatear: {{ $phone }}
                </a>
            </div>

            <!-- Direct Details -->
            <div class="checkout-card">
                <h4 style="font-family: var(--font-display); font-size: 1.2rem; margin-bottom: var(--space-4);">Canales Oficiales</h4>
                <div style="display: flex; flex-direction: column; gap: var(--space-3); font-size: var(--text-sm);">
                    <div>
                        <strong style="color: var(--color-text);">Instagram:</strong>
                        <a href="https://instagram.com/by.moraia" target="_blank" rel="noopener" style="color: var(--color-primary-dark); font-weight: 600; margin-left: 5px;">{{ $instagram }}</a>
                    </div>
                    <div>
                        <strong style="color: var(--color-text);">Email:</strong>
                        <span style="color: var(--color-text-light); margin-left: 5px;">{{ $email }}</span>
                    </div>
                    <div>
                        <strong style="color: var(--color-text);">Ubicación:</strong>
                        <span style="color: var(--color-text-light); margin-left: 5px;">Caracas, Venezuela</span>
                    </div>
                    <div>
                        <strong style="color: var(--color-text);">Horario de Atención:</strong>
                        <span style="color: var(--color-text-light); margin-left: 5px;">Lunes a Sábado, 9:00 AM - 7:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
