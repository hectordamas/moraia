<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-brand-col">
                <a href="{{ route('home') }}" class="footer-logo" aria-label="Moraia Home">
                    <img src="{{ asset('images/branding/logo_moraia_navbar_oscuro.png') }}" alt="MORAIA">
                </a>
                <p>
                    Moraia existe para reunir en una sola caja todo lo que una mujer necesita para consentirse: lo que se pone, lo que la embellece y lo que la hace sonreír al abrirla.
                </p>
                <div class="footer-social-links">
                    <a href="https://instagram.com/by.moraia" target="_blank" rel="noopener" class="footer-social-icon" aria-label="Instagram Moraia">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/584120206548" target="_blank" rel="noopener" class="footer-social-icon" aria-label="WhatsApp Moraia">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Shop Navigation -->
            <div>
                <h4 class="footer-col-title">Explorar</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('shop') }}" class="footer-link">Catálogo Completo</a></li>
                    <li><a href="{{ route('shop', ['category' => 'pijamas']) }}" class="footer-link">Pijamas de Satén</a></li>
                    <li><a href="{{ route('shop', ['category' => 'lenceria']) }}" class="footer-link">Lencería Fina</a></li>
                    <li><a href="{{ route('shop', ['category' => 'belleza']) }}" class="footer-link">Belleza & Skincare</a></li>
                    <li><a href="{{ route('shop', ['category' => 'regalos']) }}" class="footer-link">Cajas de Regalo</a></li>
                    <li><a href="{{ route('shop', ['line' => 'intimo']) }}" class="footer-link">Moraia Íntimo (18+)</a></li>
                </ul>
            </div>

            <!-- Customer Service / Info -->
            <div>
                <h4 class="footer-col-title">Información</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}" class="footer-link">Nuestra Historia</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link">Contacto & Atención</a></li>
                    <li><a href="{{ route('shipping-returns') }}" class="footer-link">Envíos & Delivery</a></li>
                    <li><a href="{{ route('privacy') }}" class="footer-link">Política de Privacidad</a></li>
                    <li><a href="{{ route('terms') }}" class="footer-link">Términos y Condiciones</a></li>
                </ul>
            </div>

            <!-- Delivery & Direct Reach -->
            <div>
                <h4 class="footer-col-title">Atención Personalizada</h4>
                <p style="color: #BDB3AF; font-size: var(--text-sm); line-height: 1.6;">
                    📍 <strong>Caracas:</strong> Delivery propio en 24h.<br>
                    🇻🇪 <strong>Venezuela:</strong> Envíos nacionales por MRW, Zoom y Tealca.<br><br>
                    💬 WhatsApp: <a href="https://wa.me/584120206548" target="_blank" rel="noopener" style="color: var(--color-primary-light); font-weight: 600;">+58 412 020 6548</a><br>
                    ✉️ Email: By.moraia@gmail.com
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; {{ date('Y') }} MORAIA. Todos los derechos reservados. Fundada por Airan Zambrano.
            </div>
            <div>
                <a href="{{ route('admin.login') }}" style="color: #6E6460; font-size: 0.75rem; text-decoration: none;">Acceso Privado</a>
            </div>
        </div>
    </div>
</footer>
