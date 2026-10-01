@extends('layouts.app')

@section('title', 'MORAIA | El arte de consentirte en cada detalle')
@section('meta_description', 'Descubre Moraia: un universo de pijamas de satén, lencería delicada, belleza, bienestar íntimo y cajas de regalo inolvidables en Venezuela.')

@section('content')
<!-- 1. EDITORIAL HERO SLIDER -->
<section class="hero-slider-section" aria-label="Destacados de temporada">
    <div class="hero-slider-container">
        <!-- Slide 1 -->
        <div class="hero-slide active">
            <img src="{{ asset('images/hero/hero-slide-1.jpg') }}" alt="Moraia Colección Satén y Lencería" class="hero-slide-bg">
            <div class="hero-slide-overlay"></div>
            <div class="container">
                <div class="hero-slide-content">
                    <span class="hero-slide-tag">Nueva Colección</span>
                    <h1 class="hero-slide-title">El arte de consentirte en cada detalle</h1>
                    <p class="hero-slide-desc">
                        Descubre piezas en satén ultra suave y lencería delicada creadas para celebrar tu feminidad y bienestar diario.
                    </p>
                    <div class="hero-slide-actions">
                        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">Explorar Tienda</a>
                        <a href="{{ route('shop', ['category' => 'pijamas']) }}" class="btn btn-outline btn-lg">Ver Pijamas</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="hero-slide">
            <img src="{{ asset('images/hero/hero-slide-2.jpg') }}" alt="Regala una experiencia Moraia" class="hero-slide-bg">
            <div class="hero-slide-overlay"></div>
            <div class="container">
                <div class="hero-slide-content">
                    <span class="hero-slide-tag">Experiencias de Regalo</span>
                    <h2 class="hero-slide-title">Regala un momento verdaderamente inolvidable</h2>
                    <p class="hero-slide-desc">
                        Cajas de regalo con empaque de lujo y tarjeta personalizada para sorprender a quien más quieres o para tu propio disfrute.
                    </p>
                    <div class="hero-slide-actions">
                        <a href="{{ route('shop', ['category' => 'regalos']) }}" class="btn btn-primary btn-lg">Ver Cajas de Regalo</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline btn-lg">Asesoría WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="hero-slide">
            <img src="{{ asset('images/hero/hero-slide-3.jpg') }}" alt="Belleza y Bienestar Íntimo Moraia" class="hero-slide-bg">
            <div class="hero-slide-overlay"></div>
            <div class="container">
                <div class="hero-slide-content">
                    <span class="hero-slide-tag">Amor Propio & Autocuidado</span>
                    <h2 class="hero-slide-title">Cuidado, belleza & bienestar íntimo</h2>
                    <p class="hero-slide-desc">
                        Fórmulas limpias, aromas relajantes y una selección refinada y discreta pensada para tu ritual personal.
                    </p>
                    <div class="hero-slide-actions">
                        <a href="{{ route('shop', ['category' => 'belleza']) }}" class="btn btn-primary btn-lg">Ver Belleza & Skincare</a>
                        <a href="{{ route('shop', ['line' => 'intimo']) }}" class="btn btn-outline btn-lg">Moraia Íntimo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider Navigation Controls -->
    <div class="hero-slider-nav">
        <div class="container hero-slider-nav-inner">
            <div class="hero-slider-dots">
                <button type="button" class="slider-dot active" aria-label="Slide 1"></button>
                <button type="button" class="slider-dot" aria-label="Slide 2"></button>
                <button type="button" class="slider-dot" aria-label="Slide 3"></button>
            </div>
            <div class="hero-slider-arrows">
                <button type="button" class="slider-arrow-btn slider-prev" aria-label="Slide Anterior">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button type="button" class="slider-arrow-btn slider-next" aria-label="Slide Siguiente">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- 2. CATEGORIES SHOWCASE -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Universo Moraia</span>
            <h2 class="section-title">Explora Nuestras Categorías</h2>
            <p class="section-subtitle">
                Diseñadas para acompañarte en cada momento de descanso, belleza y consentirte.
            </p>
        </div>

        <div class="grid-categories">
            @foreach($categories as $category)
                <a href="{{ route('category', $category->slug) }}" class="card-category">
                    <img src="{{ asset($category->image_path) }}" alt="{{ $category->name }}" class="card-category-img" loading="lazy">
                    <div class="card-category-overlay">
                        <h3 class="card-category-title">{{ $category->name }}</h3>
                        <span class="card-category-link">
                            Descubrir Colección &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 3. FEATURED PRODUCTS -->
<section class="section-padding" style="background-color: #FAF5F2;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Selección Especial</span>
            <h2 class="section-title">Los Favoritos de Moraia</h2>
            <p class="section-subtitle">
                Piezas esenciales con valoraciones excepcionales y acabados de alta costura.
            </p>
        </div>

        <div class="product-grid">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="text-center" style="margin-top: var(--space-12);">
            <a href="{{ route('shop') }}" class="btn btn-secondary btn-lg">Ver Todos los Productos</a>
        </div>
    </div>
</section>

<!-- 4. BRAND STORY & MANIFESTO -->
<section class="section-padding">
    <div class="container">
        <div class="grid-brand-story">
            <div class="brand-story-img-wrap">
                <div class="brand-story-frame">
                    <img src="{{ asset('images/branding/philosophy-box.jpg') }}" alt="Moraia Cajas de Regalo & Experiencias" class="brand-story-img" loading="lazy">
                    <div class="brand-story-overlay"></div>
                    <div class="brand-story-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                        </svg>
                        <span>Selección Exclusiva</span>
                    </div>
                </div>

                <div class="brand-story-float-card">
                    <div class="flex items-center gap-2" style="margin-bottom: 4px;">
                        <span style="font-size: 1.1rem;">🎁</span>
                        <span style="font-family: var(--font-display); font-size: 1.05rem; font-weight: 700; color: var(--color-primary-dark); line-height: 1.2;">
                            "Regala Experiencia"
                        </span>
                    </div>
                    <span style="font-size: 0.74rem; color: var(--color-text-muted); display: block; margin-bottom: 4px;">
                        Caracas & Envíos Nacionales
                    </span>
                    <div style="font-size: 0.7rem; color: #D4AF37; letter-spacing: 1px; font-weight: 700;">
                        ★★★★★ <span style="color: var(--color-text-muted); font-weight: 500; font-size: 0.68rem;">Empaque de Lujo</span>
                    </div>
                </div>
            </div>

            <div>
                <span class="section-tag">Nuestra Filosofía</span>
                <h2 style="font-size: clamp(1.85rem, 4vw, 2.75rem); margin-bottom: var(--space-6); line-height: 1.2;">
                    Un universo pensado exclusivamente para consentirte
                </h2>
                <p style="font-size: 1.15rem; line-height: 1.8; color: var(--color-text-light); margin-bottom: var(--space-6);">
                    <em>"{{ $manifesto }}"</em>
                </p>
                <p style="color: var(--color-text-muted); line-height: 1.7; margin-bottom: var(--space-8);">
                    En Moraia creemos que cada mujer merece momentos de calma, belleza y sensualidad libre de clichés. Cada prenda, aroma y detalle es seleccionado con un estándar riguroso de calidad y delicadeza.
                </p>
                <div class="brand-story-founder-row">
                    <div>
                        <div style="font-family: var(--font-display); font-size: 1.4rem; font-weight: 700; color: var(--color-text);">Airan Zambrano</div>
                        <div style="font-size: var(--text-xs); color: var(--color-primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">Fundadora & CEO</div>
                    </div>
                    <a href="{{ route('about') }}" class="btn btn-outline-primary btn-sm">Conoce Nuestra Historia</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. CLIENT REVIEWS & EXPERIENCES -->
<section class="section-padding" style="background-color: #FAF5F2;">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">Experiencias Reales</span>
            <h2 class="section-title">Lo Que Ellas Dicen de Moraia</h2>
            <p class="section-subtitle">
                Historias y valoraciones de mujeres que han transformado sus momentos de descanso y cuidado personal.
            </p>
        </div>

        <div class="grid-reviews">
            <div class="review-card">
                <div>
                    <div class="review-stars">★★★★★</div>
                    <p class="review-quote">
                        "La pijama de satén Aura Rose es una maravilla de suave. Llegó el mismo día en Caracas en un empaque impecable. La atención por WhatsApp fue súper rápida y atenta."
                    </p>
                </div>
                <div class="review-footer">
                    <div>
                        <div class="review-author-name">Valentina M.</div>
                        <div class="review-author-location">📍 Caracas, Venezuela</div>
                    </div>
                    <span class="review-product-tag">Pijama Aura Rose</span>
                </div>
            </div>

            <div class="review-card">
                <div>
                    <div class="review-stars">★★★★★</div>
                    <p class="review-quote">
                        "Tenía dudas con la talla del bralette y la lencería, pero me asesoraron al instante por WhatsApp. El envío por MRW llegó perfecto, rápido y con total discreción."
                    </p>
                </div>
                <div class="review-footer">
                    <div>
                        <div class="review-author-name">Camila R.</div>
                        <div class="review-author-location">📍 Valencia, Carabobo</div>
                    </div>
                    <span class="review-product-tag">Bralette Chantilly</span>
                </div>
            </div>

            <div class="review-card">
                <div>
                    <div class="review-stars">★★★★★</div>
                    <p class="review-quote">
                        "El aroma de la bruma Calm Petals y la textura del lip oil superaron mis expectativas. Se siente la calidad, el detalle y la delicadeza en cada producto de Moraia."
                    </p>
                </div>
                <div class="review-footer">
                    <div>
                        <div class="review-author-name">Isabella G.</div>
                        <div class="review-author-location">📍 Lechería, Anzoátegui</div>
                    </div>
                    <span class="review-product-tag">Bruma & Lip Oil</span>
                </div>
            </div>
        </div>

        <div class="reviews-trust-bar">
            <!-- Metric 1: Happy Clients -->
            <div class="trust-metric-item">
                <div class="trust-metric-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </div>
                <div class="trust-metric-content">
                    <span class="trust-metric-number">+1,200</span>
                    <span class="trust-metric-label">Clientas Felices</span>
                </div>
            </div>

            <!-- Metric 2: Satisfaction -->
            <div class="trust-metric-item">
                <div class="trust-metric-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
                <div class="trust-metric-content">
                    <span class="trust-metric-number">99.4%</span>
                    <span class="trust-metric-label">Satisfacción en Calidad</span>
                </div>
            </div>

            <!-- Metric 3: Fast Delivery -->
            <div class="trust-metric-item">
                <div class="trust-metric-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V3.75A1.125 1.125 0 0 0 13.125 2.625h-7.5A1.125 1.125 0 0 0 4.5 3.75v10.5" />
                    </svg>
                </div>
                <div class="trust-metric-content">
                    <span class="trust-metric-number">24h</span>
                    <span class="trust-metric-label">Delivery en Caracas</span>
                </div>
            </div>

            <!-- Metric 4: Discreet Shipping -->
            <div class="trust-metric-item">
                <div class="trust-metric-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <div class="trust-metric-content">
                    <span class="trust-metric-number">100%</span>
                    <span class="trust-metric-label">Envíos Discretos</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. MORAIA ÍNTIMO DISCREET HIGHLIGHT -->
<section class="section-padding">
    <div class="container">
        <div class="intimo-banner-card">
            <div class="intimo-banner-content" style="position: relative; z-index: 2;">
                <span class="badge badge-rose" style="margin-bottom: var(--space-4); letter-spacing: 0.08em; text-transform: uppercase;">
                    Línea Exclusiva (18+)
                </span>
                
                <h2 style="color: #FFFFFF; font-family: var(--font-display); font-size: clamp(2rem, 4.5vw, 3rem); line-height: 1.15; margin-bottom: var(--space-4); text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                    Moraia Íntimo: Delicadeza, Sensualidad & Bienestar
                </h2>
                
                <p style="color: #E2D7D4; font-size: 1.08rem; line-height: 1.75; margin-bottom: var(--space-2); text-shadow: 0 1px 4px rgba(0,0,0,0.4);">
                    Una selección discreta, elegante y sofisticada de lencería fina, batas de satén y bienestar íntimo femenino para reconectar con tu esencia.
                </p>

                <div class="intimo-features-row">
                    <div class="intimo-feature-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <span>Empaque 100% Discreto</span>
                    </div>
                    <div class="intimo-feature-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                        <span>Acabados de Alta Costura</span>
                    </div>
                    <div class="intimo-feature-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .94-3.138 7.377 7.377 0 0 1-1.876-4.517C4 8.444 8.03 4.75 13 4.75s9 3.694 9 7.25Z" />
                        </svg>
                        <span>Asesoría Privada WhatsApp</span>
                    </div>
                </div>

                <div class="intimo-banner-actions">
                    <a href="{{ route('shop', ['line' => 'intimo']) }}" class="btn btn-primary btn-lg">
                        Explorar Moraia Íntimo
                    </a>
                    <a href="https://wa.me/584120206548?text=Hola%20Moraia%2C%20quisiera%20solicitar%20el%20cat%C3%A1logo%20privado%20de%20Moraia%20%C3%8Dntimo." target="_blank" rel="noopener" class="btn btn-outline btn-lg" style="border-color: rgba(255,255,255,0.5); color: #FFFFFF; background-color: rgba(0, 0, 0, 0.25); backdrop-filter: blur(4px);">
                        Catálogo Privado por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. TRUST & SHIPPING BENEFITS -->
<section class="section-padding-sm" style="background-color: var(--color-surface); border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <div class="grid-benefits">
            <div class="benefit-card">
                <div class="benefit-icon-wrap">
                    <!-- Luxury Fast Delivery Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </div>
                <h4 class="benefit-title">Delivery en Caracas</h4>
                <p class="benefit-desc">Entrega en 24h con mensajería propia y seguimiento directo.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon-wrap">
                    <!-- Nationwide Insured Shipping Box Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </div>
                <h4 class="benefit-title">Envíos Nacionales</h4>
                <p class="benefit-desc">Envíos asegurados a toda Venezuela por MRW, Zoom y Tealca.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon-wrap">
                    <!-- Luxury Gift Box Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H4.5a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </div>
                <h4 class="benefit-title">Empaque de Regalo</h4>
                <p class="benefit-desc">Cajas rígidas con lazo de satén y tarjeta personalizada.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon-wrap">
                    <!-- Personalized Concierge Attention Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 4.09 4.09 0 0 0 .54-1.425A8.906 8.906 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                </div>
                <h4 class="benefit-title">Atención Cercana</h4>
                <p class="benefit-desc">Asesoría personalizada y coordinación directa por WhatsApp.</p>
            </div>
        </div>
    </div>
</section>
@endsection
