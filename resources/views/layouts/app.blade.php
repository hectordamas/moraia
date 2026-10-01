<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'MORAIA | El arte de consentirte en cada detalle')</title>
    <meta name="description" content="@yield('meta_description', 'Moraia es un universo de productos femeninos de belleza, pijamas de satén, lencería sofisticada y cajas de regalo inolvidables.')">
    
    <!-- Open Graph / Social Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'MORAIA | El arte de consentirte')">
    <meta property="og:description" content="@yield('meta_description', 'Moraia — Lencería, Pijamas, Belleza & Regalos inolvidables.')">
    <meta property="og:image" content="@yield('og_image', asset('images/branding/logo_moraia_navbar_oscuro.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/branding/favicon.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/branding/isotipo.jpeg') }}">

    <!-- Google Fonts: Cormorant Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS System -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}?v={{ file_exists(public_path('css/variables.css')) ? filemtime(public_path('css/variables.css')) : '2.0' }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}?v={{ file_exists(public_path('css/base.css')) ? filemtime(public_path('css/base.css')) : '2.0' }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}?v={{ file_exists(public_path('css/components.css')) ? filemtime(public_path('css/components.css')) : '2.0' }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}?v={{ file_exists(public_path('css/layout.css')) ? filemtime(public_path('css/layout.css')) : '2.0' }}">
    <link rel="stylesheet" href="{{ asset('css/slider.css') }}?v={{ file_exists(public_path('css/slider.css')) ? filemtime(public_path('css/slider.css')) : '2.0' }}">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}?v={{ file_exists(public_path('css/shop.css')) ? filemtime(public_path('css/shop.css')) : '2.0' }}">
    @stack('styles')

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "Organization",
      "name": "MORAIA",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/branding/logo_moraia_navbar_oscuro.png') }}",
      "sameAs": [
        "https://instagram.com/by.moraia"
      ],
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+584120206548",
        "contactType": "customer service",
        "areaServed": "VE",
        "availableLanguage": "Spanish"
      }
    }
    </script>
    @stack('structured_data')
</head>
<body>
    <!-- Top Announcement Bar -->
    <div class="announcement-bar">
        <span>{{ \App\Models\Setting::get('announcement_bar_text', '🌸 Envíos a toda Venezuela | Delivery propio en Caracas | Atención por WhatsApp') }}</span>
        <a href="https://wa.me/584120206548" target="_blank" rel="noopener">WhatsApp Directo &rarr;</a>
    </div>

    <!-- Header Navigation -->
    @include('partials.header')

    <!-- Main View Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Mini-Cart Drawer -->
    @include('partials.cart-drawer')

    <!-- Floating WhatsApp Action -->
    @include('partials.whatsapp-float')

    <!-- Toast Notifications Container -->
    <div class="toast-container" aria-live="polite"></div>

    <!-- Flash Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast("{{ session('success') }}", 'success');
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast("{{ session('error') }}", 'error');
            });
        </script>
    @endif
    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast("{{ session('info') }}", 'info');
            });
        </script>
    @endif

    <!-- JavaScript Application -->
    <script src="{{ asset('js/app.js') }}?v=1.0"></script>
    @stack('scripts')
</body>
</html>
