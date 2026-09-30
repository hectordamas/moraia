@php
    $cart = session()->get('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));
    $categoriesNav = \App\Models\Category::where('is_active', true)->withCount('products')->orderBy('sort_order')->get();
@endphp

<header class="site-header">
    <div class="container header-inner">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="header-logo" aria-label="Moraia Home">
            <img src="{{ asset('images/branding/logo_moraia_navbar_oscuro.png') }}" alt="MORAIA Logo">
        </a>

        <!-- Desktop Navigation -->
        <nav class="header-nav" aria-label="Navegación principal">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
            
            <!-- Tienda Mega Menu Item -->
            <div class="nav-item-mega">
                <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop') || request()->routeIs('category') ? 'active' : '' }}">
                    <span>Tienda</span>
                    <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <!-- Mega Menu Dropdown -->
                <div class="mega-menu-panel">
                    <div class="container">
                        <div class="mega-menu-inner">
                            <div class="mega-menu-section-header">
                                <div>
                                    <span class="mega-menu-tag">Categorías</span>
                                    <h3 class="mega-menu-title">Explora Nuestras Colecciones</h3>
                                </div>
                                <a href="{{ route('shop') }}" class="mega-menu-view-all">
                                    Ver Todo el Catálogo &rarr;
                                </a>
                            </div>

                            <div class="mega-categories-grid">
                                @foreach($categoriesNav as $cat)
                                    <a href="{{ route('category', $cat->slug) }}" class="mega-cat-card">
                                        <div class="mega-cat-img-wrap">
                                            <img src="{{ asset($cat->image_path) }}" alt="{{ $cat->name }}" loading="lazy">
                                        </div>
                                        <div class="mega-cat-details">
                                            <span class="mega-cat-title">{{ $cat->name }}</span>
                                            <span class="mega-cat-subtitle">{{ $cat->products_count }} {{ $cat->products_count === 1 ? 'producto' : 'productos' }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Nosotros</a>
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contacto</a>
        </nav>

        <!-- Header Actions -->
        <div class="header-actions">
            <!-- Search Toggle -->
            <button type="button" class="header-icon-btn" data-toggle="search" aria-label="Buscar productos">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>

            <!-- Cart Trigger -->
            <button type="button" class="header-icon-btn" data-toggle="cart" aria-label="Ver bolsa">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="cart-count-badge" style="display: {{ $cartCount > 0 ? 'flex' : 'none' }};">{{ $cartCount }}</span>
            </button>

            <!-- Mobile Hamburger -->
            <button type="button" class="mobile-menu-toggle" aria-label="Abrir menú">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Search Modal Overlay -->
    <div class="search-modal" role="dialog" aria-label="Búsqueda de productos">
        <div class="container">
            <form action="{{ route('shop') }}" method="GET" class="search-bar-wrap">
                <input type="text" name="q" class="search-input" placeholder="Buscar pijamas, lencería, belleza, regalos..." value="{{ request('q') }}" autocomplete="off">
                <button type="submit" class="search-submit-btn" aria-label="Ejecutar búsqueda">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div class="drawer-backdrop"></div>
<div class="mobile-nav-drawer" role="dialog" aria-label="Menú móvil">
    <div class="mobile-nav-header">
        <img src="{{ asset('images/branding/logo_moraia_navbar_oscuro.png') }}" alt="MORAIA" style="height: 38px;">
        <button type="button" class="mobile-nav-close" aria-label="Cerrar menú">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="mobile-nav-links">
        <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
        
        <div class="mobile-nav-accordion-item">
            <button type="button" class="mobile-accordion-btn" id="mobileShopToggle" aria-expanded="false" aria-controls="mobileShopMenu">
                <span>Tienda & Colecciones</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div class="mobile-accordion-content" id="mobileShopMenu">
                <div class="mobile-accordion-inner">
                    <a href="{{ route('shop') }}" class="mobile-sublink {{ request()->routeIs('shop') && !request('category') && !request('line') ? 'active' : '' }}">
                        <span>🛍️ Ver Todo el Catálogo</span>
                    </a>
                    @foreach($categoriesNav as $cNav)
                        <a href="{{ route('category', $cNav->slug) }}" class="mobile-sublink {{ request()->is('categoria/'.$cNav->slug) ? 'active' : '' }}">
                            <span>{{ $cNav->name }}</span>
                            <span class="mobile-sublink-count">{{ $cNav->products_count }}</span>
                        </a>
                    @endforeach
                    <a href="{{ route('shop', ['line' => 'intimo']) }}" class="mobile-sublink" style="color: var(--color-primary); font-weight: 600;">
                        <span>✨ Moraia Íntimo (18+)</span>
                    </a>
                </div>
            </div>
        </div>

        <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Nosotros</a>
        <a href="{{ route('contact') }}" class="mobile-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contacto</a>
    </div>
</div>
