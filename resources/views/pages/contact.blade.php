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
            <div class="contact-whatsapp-box">
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
                <h4 style="font-family: var(--font-display); font-size: 1.25rem; margin-bottom: var(--space-4); color: var(--color-text);">Canales Oficiales</h4>
                <div class="contact-channels-list">
                    <!-- Instagram -->
                    <a href="https://instagram.com/by.moraia" target="_blank" rel="noopener" class="contact-channel-card">
                        <div class="contact-channel-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </div>
                        <div class="contact-channel-info">
                            <span class="contact-channel-title">Instagram</span>
                            <span class="contact-channel-value">{{ $instagram }}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--color-text-muted); opacity: 0.6;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>

                    <!-- Email -->
                    <a href="mailto:{{ $email }}" class="contact-channel-card">
                        <div class="contact-channel-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="contact-channel-info">
                            <span class="contact-channel-title">Correo Electrónico</span>
                            <span class="contact-channel-value">{{ $email }}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--color-text-muted); opacity: 0.6;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <!-- Location -->
                    <div class="contact-channel-card">
                        <div class="contact-channel-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="contact-channel-info">
                            <span class="contact-channel-title">Ubicación</span>
                            <span class="contact-channel-value">Caracas, Venezuela</span>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="contact-channel-card">
                        <div class="contact-channel-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="contact-channel-info">
                            <span class="contact-channel-title">Horario de Atención</span>
                            <span class="contact-channel-value">Lunes a Sábado, 9:00 AM - 7:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
