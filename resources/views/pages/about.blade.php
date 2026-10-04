@extends('layouts.app')

@section('title', 'Nuestra Historia & Filosofía | MORAIA')
@section('meta_description', 'Conoce la historia detrás de Moraia: una marca creada para consentir a la mujer en cada detalle con lencería, pijamas y regalos inolvidables.')

@section('content')
<!-- Full-Width Page Hero (About / Story) -->
<section class="page-hero-section hero-centered">
    <div class="page-hero-vectors" aria-hidden="true">
        <svg class="cat-vector page-vector-left cat-anim-float-slow" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M40,100 C70,40 140,50 180,20 C160,80 180,140 140,180 C90,160 50,150 40,100 Z" fill="rgba(200, 116, 126, 0.08)"/>
            <circle cx="150" cy="50" r="4" fill="rgba(200, 116, 126, 0.25)" class="cat-anim-pulse"/>
        </svg>
        <svg class="cat-vector page-vector-right cat-anim-float-reverse" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="200" cy="200" r="120" stroke="rgba(200, 116, 126, 0.12)" stroke-width="1.5" stroke-dasharray="6 6"/>
            <circle cx="200" cy="200" r="70" stroke="rgba(200, 116, 126, 0.16)" stroke-width="1.5"/>
        </svg>
        <div class="page-sparkle page-sparkle-1 cat-anim-sparkle">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.4)"/></svg>
        </div>
        <div class="page-sparkle page-sparkle-2 cat-anim-sparkle" style="animation-delay: 1.8s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.3)"/></svg>
        </div>
    </div>

    <div class="container page-hero-container">
        <nav class="breadcrumbs page-hero-breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Nuestra Historia</span>
        </nav>

        <div class="page-hero-badge">
            <span class="page-badge-dot"></span>
            Manifiesto Moraia
        </div>

        <h1 class="page-hero-title">
            "El arte de consentirte en cada detalle"
        </h1>

        <p class="page-hero-desc" style="font-style: italic; font-size: 1.18rem; max-width: 780px;">
            Moraia existe para reunir en una sola caja todo lo que una mujer necesita para consentirse: lo que se pone, lo que la embellece, lo que la hace sentir deseada y lo que la hace sonreír al abrirla. Regala experiencia.
        </p>
    </div>
</section>

<div class="container section-padding" style="padding-top: var(--space-4);">

    <!-- Origin & Meaning -->
    <div class="grid-2-col" style="align-items: center; margin-bottom: var(--space-16);">
        <div>
            <span class="section-tag">Composición del Nombre</span>
            <h2 style="font-size: clamp(1.75rem, 3.5vw, 2.25rem); margin-bottom: var(--space-4);">El Origen de Moraia</h2>
            <p style="line-height: 1.8; color: var(--color-text-light); margin-bottom: var(--space-4);">
                <strong>Moraia</strong> es un nombre de sonoridad suave y femenina que transmite sofisticación, exclusividad y misterio refinado. Su etimología evoca <em>"La que Dios cuida"</em> o <em>"Tierra de la visión"</em>.
            </p>
            <p style="line-height: 1.8; color: var(--color-text-muted); margin-bottom: var(--space-6);">
                En el ámbito del diseño y la moda femenina, evoca calma, feminidad refinada y una belleza profunda que va más allá de lo superficial. Diseñado para adaptarse armónicamente a empaques de alta gama y experiencias que dejan huella.
            </p>
            <div style="background-color: #FAF5F2; padding: var(--space-5); border-radius: var(--radius-sm); border-left: 4px solid var(--color-primary);">
                <div style="font-family: var(--font-display); font-size: 1.3rem; font-weight: 600; color: var(--color-text);">Airan Zambrano</div>
                <div style="font-size: var(--text-xs); color: var(--color-primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">Fundadora & CEO</div>
            </div>
        </div>

        <div>
            <img src="{{ asset('images/branding/moraia_brand_concept.jpg') }}" alt="Moraia Experiencia de Regalo y Lujo" style="width: 100%; border-radius: var(--radius-md); box-shadow: 0 12px 36px rgba(169, 80, 88, 0.12); object-fit: cover; aspect-ratio: 4/3;">
        </div>
    </div>

    <!-- Mision & Vision Cards -->
    <div class="grid-2-col" style="margin-bottom: var(--space-16);">
        <div style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: clamp(var(--space-6), 4vw, var(--space-8)); box-shadow: var(--shadow-subtle);">
            <div style="font-size: 2.5rem; margin-bottom: 10px;">🌟</div>
            <h3 style="font-family: var(--font-display); font-size: 1.6rem; margin-bottom: var(--space-3);">Nuestra Misión</h3>
            <p style="line-height: 1.7; color: var(--color-text-light);">
                Ser para 2030 la marca de referencia en Venezuela en regalos personalizados para mujeres — reconocida no únicamente por lo que vende, sino por cómo se siente recibir una caja Moraia.
            </p>
        </div>

        <div style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: clamp(var(--space-6), 4vw, var(--space-8)); box-shadow: var(--shadow-subtle);">
            <div style="font-size: 2.5rem; margin-bottom: 10px;">✨</div>
            <h3 style="font-family: var(--font-display); font-size: 1.6rem; margin-bottom: var(--space-3);">Nuestra Visión</h3>
            <p style="line-height: 1.7; color: var(--color-text-light);">
                Diseñar y entregar experiencias de regalo personalizadas que combinen belleza, intimidad y detalle, con un servicio cercano que convierta cada pedido en un momento memorable.
            </p>
        </div>
    </div>

    <!-- Two Universes Section -->
    <div style="margin-top: var(--space-8);">
        <div class="section-header text-center" style="margin-bottom: var(--space-8);">
            <span class="section-tag">Una Marca, Dos Expresiones</span>
            <h2 class="section-title">Dos Universos Pensados para Ti</h2>
            <p class="section-subtitle">
                Diseñados para acompañarte en tus momentos de calma, descanso y en tus ocasiones más íntimas y especiales.
            </p>
        </div>

        <div class="universes-container">
            <!-- Universe 1: Moraia General -->
            <div class="universe-card universe-card-light">
                <div>
                    <div class="universe-card-header">
                        <span class="badge badge-rose" style="font-size: 0.72rem; letter-spacing: 0.06em; text-transform: uppercase;">
                            Línea Principal (16+)
                        </span>
                        <h3 class="universe-card-title">Moraia: Descanso, Cuidado & Regalos</h3>
                    </div>

                    <p class="universe-card-desc">
                        Piezas elegantes confeccionadas para consentir tu piel con satén de máxima suavidad, aromas envolventes y cajas prediseñadas para regalar con amor.
                    </p>

                    <ul class="universe-features-list">
                        <li class="universe-feature-item">
                            <span class="universe-feature-icon">✨</span>
                            <span>Pijamas de satén y seda con costuras invisibles</span>
                        </li>
                        <li class="universe-feature-item">
                            <span class="universe-feature-icon">🎁</span>
                            <span>Cajas de regalo rígidas con lazo y dedicatoria</span>
                        </li>
                        <li class="universe-feature-item">
                            <span class="universe-feature-icon">🌸</span>
                            <span>Cuidado corporal, fragancias y velas aromáticas</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <a href="{{ route('shop') }}" class="btn btn-primary btn-block">
                        Explorar Colección Moraia &rarr;
                    </a>
                </div>
            </div>

            <!-- Universe 2: Moraia Íntimo -->
            <div class="universe-card universe-card-dark">
                <div>
                    <div class="universe-card-header">
                        <span class="badge badge-rose" style="font-size: 0.72rem; letter-spacing: 0.06em; text-transform: uppercase;">
                            Línea Exclusiva (18+)
                        </span>
                        <h3 class="universe-card-title" style="color: #FFFFFF;">Moraia Íntimo: Delicadeza & Sensualidad</h3>
                    </div>

                    <p class="universe-card-desc">
                        Una selección discreta y sofisticada de lencería en encaje chantilly, transparencias sutiles y bienestar íntimo para reconectar con tu lado más deseado.
                    </p>

                    <ul class="universe-features-list">
                        <li class="universe-feature-item">
                            <span class="universe-feature-icon">🖤</span>
                            <span>Lencería fina y bralettes en encaje de alta costura</span>
                        </li>
                        <li class="universe-feature-item">
                            <span class="universe-feature-icon">🔒</span>
                            <span>Empaques 100% discretos y sin etiquetas externas</span>
                        </li>
                        <li class="universe-feature-item">
                            <span class="universe-feature-icon">💬</span>
                            <span>Asesoría personalizada y catálogo privado WhatsApp</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('shop', ['line' => 'intimo']) }}" class="btn btn-primary flex-1">
                        Ver Moraia Íntimo &rarr;
                    </a>
                    <a href="https://wa.me/584120206548?text=Hola%20Moraia%2C%20quisiera%20solicitar%20el%20cat%C3%A1logo%20privado%20de%20Moraia%20%C3%8Dntimo." target="_blank" rel="noopener" class="btn btn-outline" style="border-color: rgba(255,255,255,0.4); color: #FFFFFF; background: rgba(0,0,0,0.3); backdrop-filter: blur(4px);">
                        Catálogo Privado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
