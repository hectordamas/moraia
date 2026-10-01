@extends('layouts.admin')

@section('title', 'Punto de Venta (POS) | MORAIA Admin')
@section('page_title', 'Punto de Venta (POS)')

@section('admin_content')
<div class="pos-layout-wrapper">
    <!-- Left Column: Catalog & Products -->
    <div class="pos-catalog-section">
        <!-- Top Bar: Search & Category Chips -->
        <div class="pos-search-bar-card">
            <div class="pos-search-input-group">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="posSearchInput" placeholder="Buscar por nombre o descripción de producto..." autocomplete="off">
                <button type="button" id="posClearSearch" class="pos-clear-search" style="display: none;">&times;</button>
            </div>

            <!-- Categories Scrollable Bar -->
            <div class="pos-category-chips-wrapper">
                <button type="button" class="pos-cat-chip active" data-category-id="all">Todos ({{ $products->count() }})</button>
                @foreach($categories as $category)
                    <button type="button" class="pos-cat-chip" data-category-id="{{ $category->id }}">
                        {{ $category->name }} ({{ $products->where('category_id', $category->id)->count() }})
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Products Grid -->
        <div class="pos-products-grid" id="posProductsGrid">
            @forelse($products as $product)
                @php
                    $hasVariants = $product->variants->isNotEmpty();
                    $firstImage = $product->cover_image_url;
                    $categoryName = $product->category ? $product->category->name : 'Sin categoría';
                    $totalStock = $hasVariants ? $product->variants->sum('stock_quantity') : $product->stock_quantity;
                    $isOutOfStock = $totalStock <= 0;
                @endphp
                <div class="pos-product-card {{ $isOutOfStock ? 'out-of-stock' : '' }}" 
                     data-id="{{ $product->id }}"
                     data-name="{{ strtolower($product->name) }}"
                     data-category="{{ $product->category_id ?? 'none' }}"
                     data-price="{{ $product->price }}"
                     data-variants="{{ json_encode($product->variants) }}"
                     data-has-variants="{{ $hasVariants ? 'true' : 'false' }}"
                     data-stock="{{ $totalStock }}"
                     data-image="{{ $firstImage }}">
                    
                    <div class="pos-product-img-box">
                        <img src="{{ $firstImage }}" alt="{{ $product->name }}" loading="lazy">
                        @if($isOutOfStock)
                            <span class="pos-badge-stock out">Agotado</span>
                        @elseif($totalStock <= 3)
                            <span class="pos-badge-stock low">Últimas {{ $totalStock }}</span>
                        @else
                            <span class="pos-badge-stock in">{{ $totalStock }} disp.</span>
                        @endif

                        @if($hasVariants)
                            <span class="pos-badge-variants">{{ $product->variants->count() }} variantes</span>
                        @endif
                    </div>

                    <div class="pos-product-info">
                        <span class="pos-product-cat">{{ $categoryName }}</span>
                        <h4 class="pos-product-title" title="{{ $product->name }}">{{ $product->name }}</h4>
                        <div class="pos-product-footer">
                            <span class="pos-product-price">${{ number_format($product->price, 2) }}</span>
                            <button type="button" class="pos-btn-add" {{ $isOutOfStock ? 'disabled' : '' }}>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Añadir</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="pos-empty-catalog">
                    <p>No hay productos activos disponibles en el inventario.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Floating Ticket / Checkout Panel -->
    <div class="pos-ticket-panel">
        <!-- Pinned Header -->
        <div class="pos-ticket-header">
            <div class="pos-ticket-header-title-wrap">
                <div class="pos-ticket-live-badge">
                    <span class="pos-pulse-dot"></span>
                    <h3>Ticket de Venta</h3>
                </div>
                <span class="pos-ticket-subtitle" id="posTicketItemCount">0 artículos en la orden</span>
            </div>
            <button type="button" id="posClearCartBtn" class="pos-btn-clear-ticket" title="Vaciar Ticket">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Vaciar
            </button>
        </div>

        <!-- Scrollable Middle Body: Products List + Customer Details -->
        <div class="pos-ticket-body-scroll">
            <!-- Section 1: Hero Ordered Products Preview -->
            <div class="pos-ticket-section-title">
                <span>Productos Seleccionados</span>
                <span class="pos-items-counter-badge" id="posItemsBadge">0</span>
            </div>

            <div class="pos-ticket-items-list" id="posTicketItemsList">
                <!-- Empty state -->
                <div class="pos-ticket-empty-state" id="posEmptyCartState">
                    <div class="pos-ticket-empty-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#A95058" stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </div>
                    <h4>No has seleccionado productos</h4>
                    <p>Haz clic en los productos del catálogo a la izquierda para agregarlos al ticket.</p>
                </div>
            </div>

            <!-- Section 2: Customer & Delivery Details Accordion (Collapsed by default) -->
            <form id="posOrderForm" class="pos-ticket-form">
                @csrf

                <div class="pos-accordion-card" id="posCustomerAccordion">
                    <div class="pos-accordion-header" onclick="togglePosAccordion('posCustomerAccordion')">
                        <div class="pos-acc-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Datos del Cliente & Entrega</span>
                        </div>
                        <div class="pos-acc-header-right">
                            <span class="pos-acc-status-tag" id="posCustomerSummaryTag">Caracas • Venta Directa</span>
                            <svg class="pos-acc-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="pos-accordion-body">
                        <div class="pos-form-grid-2">
                            <div class="pos-field">
                                <label for="posCustomerName">Nombre <span class="req">*</span></label>
                                <input type="text" id="posCustomerName" name="customer_name" required placeholder="Ej. Airan">
                            </div>
                            <div class="pos-field">
                                <label for="posCustomerLastname">Apellido <span class="req">*</span></label>
                                <input type="text" id="posCustomerLastname" name="customer_lastname" required placeholder="Ej. Zambrano">
                            </div>
                        </div>

                        <div class="pos-form-grid-2">
                            <div class="pos-field">
                                <label for="posCustomerWhatsapp">WhatsApp <span class="req">*</span></label>
                                <input type="text" id="posCustomerWhatsapp" name="customer_whatsapp" required placeholder="Ej. 04241930033">
                            </div>
                            <div class="pos-field">
                                <label for="posCustomerEmail">Email (Opcional)</label>
                                <input type="email" id="posCustomerEmail" name="customer_email" placeholder="cliente@correo.com">
                            </div>
                        </div>

                        <div class="pos-form-grid-2">
                            <div class="pos-field">
                                <label for="posDeliveryMethod">Método de Entrega <span class="req">*</span></label>
                                <select id="posDeliveryMethod" name="delivery_method" required>
                                    <option value="venta_directa">Venta Directa / En Tienda ($0.00)</option>
                                    <option value="delivery_caracas">Delivery en Caracas (${{ number_format($caracasShippingFee, 2) }})</option>
                                    <option value="envio_nacional">Envío Nacional (MRW / Zoom / Tealca)</option>
                                    <option value="pickup">Pick-up / Retiro en Showroom ($0.00)</option>
                                </select>
                            </div>
                            <div class="pos-field">
                                <label for="posDeliveryCity">Ciudad / Zona <span class="req">*</span></label>
                                <input type="text" id="posDeliveryCity" name="delivery_city" required value="Caracas" placeholder="Ej. Caracas Chacao">
                            </div>
                        </div>

                        <div class="pos-field">
                            <label for="posDeliveryAddress">Dirección de Entrega / Referencia <span class="req">*</span></label>
                            <input type="text" id="posDeliveryAddress" name="delivery_address" required value="Venta directa en local" placeholder="Ej. Av. Principal de Las Mercedes, Edif...">
                        </div>

                        <div class="pos-form-grid-2">
                            <div class="pos-field">
                                <label for="posOrderStatus">Estado Inicial <span class="req">*</span></label>
                                <select id="posOrderStatus" name="status" required>
                                    <option value="Confirmada" selected>Confirmada (Pagada)</option>
                                    <option value="Pendiente">Pendiente de Pago</option>
                                    <option value="Entregada">Entregada</option>
                                </select>
                            </div>
                            <div class="pos-field">
                                <label for="posDiscountAmount">Descuento ($ USD)</label>
                                <input type="number" step="0.01" min="0" id="posDiscountAmount" name="discount_amount" value="0.00" placeholder="0.00">
                            </div>
                        </div>

                        <!-- Gift option -->
                        <div class="pos-checkbox-field">
                            <label class="pos-custom-checkbox">
                                <input type="checkbox" id="posIsGift" name="is_gift" value="1">
                                <span class="checkmark"></span>
                                <span>¿Es un pedido para regalo?</span>
                            </label>
                        </div>

                        <div id="posGiftFields" class="pos-gift-fields" style="display: none;">
                            <div class="pos-field">
                                <label for="posGiftRecipient">Nombre del Destinatario</label>
                                <input type="text" id="posGiftRecipient" name="gift_recipient_name" placeholder="Para quién es el regalo...">
                            </div>
                            <div class="pos-field">
                                <label for="posGiftMessage">Dedicatoria para la Tarjeta</label>
                                <textarea id="posGiftMessage" name="gift_card_message" rows="2" placeholder="Mensaje especial para la tarjeta de regalo..."></textarea>
                            </div>
                        </div>

                        <div class="pos-field">
                            <label for="posCustomerNotes">Notas / Observaciones Internas</label>
                            <input type="text" id="posCustomerNotes" name="customer_notes" placeholder="Ej. Pagó en efectivo / Transferencia Banesco / Pedido por Instagram...">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Pinned Bottom Summary & Checkout Button -->
        <div class="pos-ticket-summary-card">
            <div class="pos-summary-row">
                <span>Subtotal</span>
                <span id="posSummarySubtotal" class="val">$0.00</span>
            </div>
            <div class="pos-summary-row" id="posSummaryDiscountRow" style="display: none;">
                <span>Descuento</span>
                <span id="posSummaryDiscount" class="val text-danger">-$0.00</span>
            </div>
            <div class="pos-summary-row">
                <div class="pos-summary-shipping-label">
                    <span>Envío / Delivery</span>
                    <input type="number" step="0.01" min="0" id="posShippingFee" name="shipping_fee" value="0.00" class="pos-input-inline-fee" title="Costo de Envío Editable">
                </div>
                <span id="posSummaryShipping" class="val">$0.00</span>
            </div>
            <div class="pos-summary-divider"></div>
            <div class="pos-summary-row total">
                <span>Total a Cobrar</span>
                <span id="posSummaryTotal" class="pos-total-amount">$0.00</span>
            </div>

            <!-- Submit Button -->
            <button type="button" id="posSubmitBtn" class="pos-btn-checkout" disabled onclick="document.getElementById('posOrderForm').requestSubmit()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>Completar y Registrar Venta</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal: Variant Selector -->
<div id="posVariantModal" class="pos-modal-backdrop" style="display: none;">
    <div class="pos-modal-content">
        <div class="pos-modal-header">
            <div>
                <h3 id="posModalProductTitle">Seleccionar Variante</h3>
                <span id="posModalProductCategory" class="pos-modal-cat"></span>
            </div>
            <button type="button" class="pos-modal-close" onclick="closePosVariantModal()">&times;</button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-modal-product-preview">
                <img id="posModalProductImg" src="" alt="">
                <div>
                    <span class="pos-modal-base-price" id="posModalProductPrice"></span>
                    <p class="pos-modal-hint">Elige la talla, color o modelo solicitado:</p>
                </div>
            </div>

            <div class="pos-variants-list" id="posModalVariantsList">
                <!-- Dynamically generated variant buttons -->
            </div>
        </div>
        <div class="pos-modal-footer">
            <button type="button" class="pos-modal-btn-cancel" onclick="closePosVariantModal()">Cancelar</button>
            <button type="button" class="pos-modal-btn-add" id="posModalConfirmAdd" disabled>Añadir a la Orden</button>
        </div>
    </div>
</div>

<!-- Modal: Order Success / Post Sale Screen -->
<div id="posSuccessModal" class="pos-modal-backdrop" style="display: none;">
    <div class="pos-modal-content pos-modal-success">
        <div class="pos-success-icon-wrap">
            <div class="pos-success-checkmark">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="3">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
        </div>
        
        <h2>¡Venta Registrada Exitosamente!</h2>
        <p class="pos-success-code" id="posSuccessOrderCode">#MOR-2026-XXXXX</p>

        <div class="pos-success-details-card">
            <div class="pos-succ-row">
                <span>Cliente:</span>
                <strong id="posSuccessCustomer">Cliente</strong>
            </div>
            <div class="pos-succ-row">
                <span>Total Pagado:</span>
                <strong id="posSuccessTotal" class="pos-succ-total-val">$0.00</strong>
            </div>
        </div>

        <div class="pos-success-actions">
            <a id="posSuccessWhatsappBtn" href="#" target="_blank" class="pos-btn-succ-wa">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.99.54 1.776.812 2.796.813 3.179 0 5.767-2.587 5.767-5.766.001-3.187-2.575-5.77-5.767-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.42-.099.825zm-3.392-12.416c-5.514 0-10 4.486-10 10 0 1.905.534 3.687 1.458 5.215l-1.497 5.474 5.637-1.479c1.476.861 3.197 1.353 4.402 1.353 5.514 0 10-4.486 10-10s-4.486-10-10-10z"/>
                </svg>
                Enviar Comprobante por WhatsApp
            </a>
            
            <div class="pos-success-btn-grid">
                <a id="posSuccessPdfBtn" href="#" target="_blank" class="pos-btn-succ-pdf">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    Descargar Ticket PDF
                </a>
                <a id="posSuccessShowBtn" href="#" class="pos-btn-succ-view">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Ver Orden
                </a>
            </div>

            <button type="button" class="pos-btn-succ-new" onclick="resetPosForNewSale()">
                + Realizar Nueva Venta
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // State
    let cart = []; // { product_id, variant_id, name, variant_name, price, image, stock, quantity }
    const caracasFee = {{ (float)$caracasShippingFee }};
    
    // Elements
    const searchInput = document.getElementById('posSearchInput');
    const clearSearchBtn = document.getElementById('posClearSearch');
    const catChips = document.querySelectorAll('.pos-cat-chip');
    const productCards = document.querySelectorAll('.pos-product-card');
    const itemsListContainer = document.getElementById('posTicketItemsList');
    const emptyState = document.getElementById('posEmptyCartState');
    const itemCountText = document.getElementById('posTicketItemCount');
    const clearCartBtn = document.getElementById('posClearCartBtn');
    const submitBtn = document.getElementById('posSubmitBtn');
    const orderForm = document.getElementById('posOrderForm');

    // Totals Elements
    const subtotalEl = document.getElementById('posSummarySubtotal');
    const discountInput = document.getElementById('posDiscountAmount');
    const discountRow = document.getElementById('posSummaryDiscountRow');
    const discountEl = document.getElementById('posSummaryDiscount');
    const shippingSelect = document.getElementById('posDeliveryMethod');
    const shippingFeeInput = document.getElementById('posShippingFee');
    const shippingEl = document.getElementById('posSummaryShipping');
    const totalEl = document.getElementById('posSummaryTotal');

    // Modals
    const variantModal = document.getElementById('posVariantModal');
    const successModal = document.getElementById('posSuccessModal');
    let currentSelectedProduct = null;
    let currentSelectedVariant = null;

    // Delivery Method Change
    shippingSelect.addEventListener('change', function() {
        const method = this.value;
        const addressInput = document.getElementById('posDeliveryAddress');
        const cityInput = document.getElementById('posDeliveryCity');

        if (method === 'delivery_caracas') {
            shippingFeeInput.value = caracasFee.toFixed(2);
            if (!cityInput.value || cityInput.value === 'Caracas') cityInput.value = 'Caracas';
            if (addressInput.value === 'Venta directa en local') addressInput.value = '';
        } else if (method === 'venta_directa') {
            shippingFeeInput.value = '0.00';
            cityInput.value = 'Caracas';
            addressInput.value = 'Venta directa en local';
        } else if (method === 'pickup') {
            shippingFeeInput.value = '0.00';
            cityInput.value = 'Caracas';
            addressInput.value = 'Retiro en Showroom';
        } else if (method === 'envio_nacional') {
            shippingFeeInput.value = '0.00'; // Cobro en destino
            if (addressInput.value === 'Venta directa en local') addressInput.value = '';
        }
        recalculateTotals();
    });

    // Gift checkbox toggle
    const giftCheckbox = document.getElementById('posIsGift');
    const giftFields = document.getElementById('posGiftFields');
    if (giftCheckbox) {
        giftCheckbox.addEventListener('change', function() {
            giftFields.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Live Search Filter
    searchInput.addEventListener('input', filterProducts);
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        clearSearchBtn.style.display = 'none';
        filterProducts();
    });

    // Category Filter Chips
    catChips.forEach(chip => {
        chip.addEventListener('click', function() {
            catChips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            filterProducts();
        });
    });

    function filterProducts() {
        const query = searchInput.value.trim().toLowerCase();
        clearSearchBtn.style.display = query ? 'block' : 'none';
        const activeCat = document.querySelector('.pos-cat-chip.active')?.getAttribute('data-category-id') || 'all';

        productCards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const cat = card.getAttribute('data-category') || '';

            const matchesQuery = !query || name.includes(query);
            const matchesCat = (activeCat === 'all') || (cat === activeCat);

            if (matchesQuery && matchesCat) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Product Card Click / Add to Cart
    productCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (card.classList.contains('out-of-stock')) return;

            const productId = parseInt(card.getAttribute('data-id'));
            const productName = card.querySelector('.pos-product-title').textContent.trim();
            const productPrice = parseFloat(card.getAttribute('data-price'));
            const productImage = card.getAttribute('data-image');
            const hasVariants = card.getAttribute('data-has-variants') === 'true';
            const variantsData = JSON.parse(card.getAttribute('data-variants') || '[]');
            const stock = parseInt(card.getAttribute('data-stock'));

            if (hasVariants && variantsData.length > 0) {
                // Open variant modal
                openPosVariantModal({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    image: productImage,
                    variants: variantsData,
                    category: card.querySelector('.pos-product-cat').textContent
                });
            } else {
                // Directly add
                addToCart({
                    product_id: productId,
                    variant_id: null,
                    name: productName,
                    variant_name: null,
                    price: productPrice,
                    image: productImage,
                    stock: stock,
                    quantity: 1
                });
            }
        });
    });

    // Variant Modal Handling
    let selectedVariantsByType = {};

    window.openPosVariantModal = function(product) {
        currentSelectedProduct = product;
        selectedVariantsByType = {};

        document.getElementById('posModalProductTitle').textContent = product.name;
        document.getElementById('posModalProductCategory').textContent = product.category;
        document.getElementById('posModalProductImg').src = product.image;
        
        const listContainer = document.getElementById('posModalVariantsList');
        listContainer.innerHTML = '';

        const confirmBtn = document.getElementById('posModalConfirmAdd');
        confirmBtn.disabled = true;

        // Group variants by type
        const grouped = {};
        product.variants.forEach(v => {
            const type = v.variant_type || 'opción';
            if (!grouped[type]) grouped[type] = [];
            grouped[type].push(v);
        });

        const groupTypes = Object.keys(grouped);

        groupTypes.forEach(type => {
            const groupWrap = document.createElement('div');
            groupWrap.className = 'pos-variant-group';

            const headerEl = document.createElement('div');
            headerEl.className = 'pos-var-group-title';
            headerEl.innerHTML = `
                <span>${type.charAt(0).toUpperCase() + type.slice(1)}:</span>
                <span class="pos-var-selected-label" id="pos-label-${type}">Selecciona una opción</span>
            `;
            groupWrap.appendChild(headerEl);

            const optionsGrid = document.createElement('div');
            optionsGrid.className = 'pos-var-group-options';

            grouped[type].forEach(variant => {
                const isOutOfStock = variant.stock_quantity <= 0;
                const priceMod = parseFloat(variant.price_modifier || 0);

                const opt = document.createElement('div');
                opt.className = `pos-variant-option ${isOutOfStock ? 'disabled' : ''}`;
                opt.innerHTML = `
                    <div class="pos-var-info">
                        <strong>${variant.name}</strong>
                    </div>
                    <div class="pos-var-meta">
                        ${priceMod > 0 ? `<span class="pos-var-price">+${priceMod.toFixed(2)}</span>` : ''}
                        <span class="pos-var-stock ${isOutOfStock ? 'out' : ''}">${isOutOfStock ? 'Agotado' : variant.stock_quantity + ' disp.'}</span>
                    </div>
                `;

                if (!isOutOfStock) {
                    opt.addEventListener('click', function() {
                        optionsGrid.querySelectorAll('.pos-variant-option').forEach(o => o.classList.remove('selected'));
                        opt.classList.add('selected');
                        selectedVariantsByType[type] = variant;
                        document.getElementById(`pos-label-${type}`).textContent = variant.name;
                        updatePosModalPrice(groupTypes);
                    });
                }

                optionsGrid.appendChild(opt);
            });

            groupWrap.appendChild(optionsGrid);
            listContainer.appendChild(groupWrap);

            // Auto-select first in stock option of each group
            const firstInStock = optionsGrid.querySelector('.pos-variant-option:not(.disabled)');
            if (firstInStock) {
                firstInStock.click();
            }
        });

        updatePosModalPrice(groupTypes);
        variantModal.style.display = 'flex';
    };

    function updatePosModalPrice(groupTypes) {
        const confirmBtn = document.getElementById('posModalConfirmAdd');
        const selectedList = Object.values(selectedVariantsByType);

        // Check if all groups have a selection
        const allSelected = groupTypes.every(t => !!selectedVariantsByType[t]);
        confirmBtn.disabled = !allSelected;

        let extraPrice = 0;
        selectedList.forEach(v => {
            extraPrice += parseFloat(v.price_modifier || 0);
        });

        const finalPrice = (currentSelectedProduct ? currentSelectedProduct.price : 0) + extraPrice;
        document.getElementById('posModalProductPrice').textContent = '$' + finalPrice.toFixed(2);
    }

    window.closePosVariantModal = function() {
        variantModal.style.display = 'none';
        currentSelectedProduct = null;
        selectedVariantsByType = {};
    };

    document.getElementById('posModalConfirmAdd').addEventListener('click', function() {
        if (!currentSelectedProduct) return;

        const selectedList = Object.values(selectedVariantsByType);
        if (selectedList.length === 0) return;

        let extraPrice = 0;
        let minStock = 9999;
        const variantParts = [];

        selectedList.forEach(v => {
            extraPrice += parseFloat(v.price_modifier || 0);
            if (v.stock_quantity < minStock) minStock = v.stock_quantity;
            variantParts.push(`${v.variant_type.charAt(0).toUpperCase() + v.variant_type.slice(1)}: ${v.name}`);
        });

        const finalPrice = currentSelectedProduct.price + extraPrice;
        const variantDetails = variantParts.join(' | ');
        const primaryVariantId = selectedList[0] ? selectedList[0].id : null;

        addToCart({
            product_id: currentSelectedProduct.id,
            variant_id: primaryVariantId,
            name: currentSelectedProduct.name,
            variant_name: variantDetails,
            price: finalPrice,
            image: currentSelectedProduct.image,
            stock: minStock,
            quantity: 1
        });

        closePosVariantModal();
    });

    // Cart Management
    function addToCart(item) {
        const existingIndex = cart.findIndex(i => i.product_id === item.product_id && i.variant_id === item.variant_id);

        if (existingIndex > -1) {
            if (cart[existingIndex].quantity + 1 > cart[existingIndex].stock) {
                alert(`No hay suficiente inventario disponible (${cart[existingIndex].stock} unidades máximo).`);
                return;
            }
            cart[existingIndex].quantity += 1;
        } else {
            cart.push(item);
        }

        renderCart();
    }

    function renderCart() {
        itemsListContainer.innerHTML = '';
        const itemsBadge = document.getElementById('posItemsBadge');

        if (cart.length === 0) {
            itemsListContainer.appendChild(emptyState);
            emptyState.style.display = 'block';
            itemCountText.textContent = '0 artículos en la orden';
            if (itemsBadge) itemsBadge.textContent = '0';
            submitBtn.disabled = true;
        } else {
            emptyState.style.display = 'none';
            const totalQty = cart.reduce((acc, i) => acc + i.quantity, 0);
            itemCountText.textContent = `${totalQty} ${totalQty === 1 ? 'artículo' : 'artículos'} (${cart.length} productos)`;
            if (itemsBadge) itemsBadge.textContent = totalQty;
            submitBtn.disabled = false;

            cart.forEach((item, index) => {
                const itemEl = document.createElement('div');
                itemEl.className = 'pos-ticket-item';
                itemEl.innerHTML = `
                    <div class="pos-item-thumb-box">
                        <img src="${item.image}" alt="${item.name}" class="pos-item-thumb">
                    </div>
                    <div class="pos-item-details">
                        <span class="pos-item-title" title="${item.name}">${item.name}</span>
                        ${item.variant_name ? `<span class="pos-item-variant-pill">${item.variant_name}</span>` : ''}
                        <div class="pos-item-pricing-meta">
                            <span class="pos-item-unit-price">$${item.price.toFixed(2)} c/u</span>
                            <span class="pos-item-total">$${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                    </div>
                    <div class="pos-item-actions">
                        <div class="pos-qty-control">
                            <button type="button" class="pos-qty-btn minus" data-index="${index}" title="Disminuir">-</button>
                            <span class="pos-qty-num">${item.quantity}</span>
                            <button type="button" class="pos-qty-btn plus" data-index="${index}" title="Aumentar">+</button>
                        </div>
                        <button type="button" class="pos-btn-remove-item" data-index="${index}" title="Eliminar">&times;</button>
                    </div>
                `;
                itemsListContainer.appendChild(itemEl);
            });

            // Bind Qty Buttons
            itemsListContainer.querySelectorAll('.pos-qty-btn.minus').forEach(b => {
                b.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const idx = parseInt(this.getAttribute('data-index'));
                    if (cart[idx].quantity > 1) {
                        cart[idx].quantity -= 1;
                    } else {
                        cart.splice(idx, 1);
                    }
                    renderCart();
                });
            });

            itemsListContainer.querySelectorAll('.pos-qty-btn.plus').forEach(b => {
                b.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const idx = parseInt(this.getAttribute('data-index'));
                    if (cart[idx].quantity + 1 > cart[idx].stock) {
                        alert(`Stock máximo alcanzado (${cart[idx].stock} unidades).`);
                        return;
                    }
                    cart[idx].quantity += 1;
                    renderCart();
                });
            });

            itemsListContainer.querySelectorAll('.pos-btn-remove-item').forEach(b => {
                b.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const idx = parseInt(this.getAttribute('data-index'));
                    cart.splice(idx, 1);
                    renderCart();
                });
            });
        }

        recalculateTotals();
    }

    const custNameInput = document.getElementById('posCustomerName');
    const custCityInput = document.getElementById('posDeliveryCity');
    function updateCustomerSummaryTag() {
        const name = custNameInput ? custNameInput.value.trim() : '';
        const city = custCityInput ? custCityInput.value.trim() : 'Caracas';
        const methodEl = document.getElementById('posDeliveryMethod');
        const methodText = methodEl ? methodEl.options[methodEl.selectedIndex]?.text.split('(')[0].trim() : 'Venta Directa';
        const tagEl = document.getElementById('posCustomerSummaryTag');
        if (tagEl) {
            tagEl.textContent = name ? `${name} • ${city}` : `${city} • ${methodText}`;
        }
    }
    if (custNameInput) custNameInput.addEventListener('input', updateCustomerSummaryTag);
    if (custCityInput) custCityInput.addEventListener('input', updateCustomerSummaryTag);
    shippingSelect.addEventListener('change', updateCustomerSummaryTag);

    clearCartBtn.addEventListener('click', function() {
        if (cart.length === 0) return;
        if (confirm('¿Deseas vaciar todos los productos del ticket?')) {
            cart = [];
            renderCart();
        }
    });

    // Calculations
    discountInput.addEventListener('input', recalculateTotals);
    shippingFeeInput.addEventListener('input', recalculateTotals);

    function recalculateTotals() {
        const subtotal = cart.reduce((acc, i) => acc + (i.price * i.quantity), 0);
        const discount = parseFloat(discountInput.value) || 0;
        const shipping = parseFloat(shippingFeeInput.value) || 0;
        const total = Math.max(0, (subtotal - discount) + shipping);

        subtotalEl.textContent = '$' + subtotal.toFixed(2);
        
        if (discount > 0) {
            discountRow.style.display = 'flex';
            discountEl.textContent = '-$' + discount.toFixed(2);
        } else {
            discountRow.style.display = 'none';
        }

        shippingEl.textContent = '$' + shipping.toFixed(2);
        totalEl.textContent = '$' + total.toFixed(2);
    }

    // Toggle Accordion Helper
    window.togglePosAccordion = function(id) {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('open');
    };

    // Form Submission / Store Order
    orderForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (cart.length === 0) {
            alert('Debes agregar al menos un producto a la orden.');
            return;
        }

        const submitBtnText = submitBtn.querySelector('span');
        const originalText = submitBtnText.textContent;
        submitBtn.disabled = true;
        submitBtnText.textContent = 'Procesando orden...';

        const formData = {
            _token: document.querySelector('input[name="_token"]').value,
            customer_name: document.getElementById('posCustomerName').value.trim(),
            customer_lastname: document.getElementById('posCustomerLastname').value.trim(),
            customer_whatsapp: document.getElementById('posCustomerWhatsapp').value.trim(),
            customer_email: document.getElementById('posCustomerEmail').value.trim() || null,
            delivery_method: document.getElementById('posDeliveryMethod').value,
            delivery_city: document.getElementById('posDeliveryCity').value.trim(),
            delivery_address: document.getElementById('posDeliveryAddress').value.trim(),
            customer_notes: document.getElementById('posCustomerNotes').value.trim() || null,
            status: document.getElementById('posOrderStatus').value,
            shipping_fee: parseFloat(shippingFeeInput.value) || 0,
            discount_amount: parseFloat(discountInput.value) || 0,
            is_gift: giftCheckbox.checked ? 1 : 0,
            gift_recipient_name: document.getElementById('posGiftRecipient')?.value.trim() || null,
            gift_card_message: document.getElementById('posGiftMessage')?.value.trim() || null,
            items: cart.map(i => ({
                product_id: i.product_id,
                variant_id: i.variant_id,
                variant_details: i.variant_name,
                quantity: i.quantity,
                unit_price: i.price
            }))
        };

        try {
            const response = await fetch("{{ route('admin.pos.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': formData._token
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Show success modal
                document.getElementById('posSuccessOrderCode').textContent = '#' + result.order_code;
                document.getElementById('posSuccessCustomer').textContent = result.customer_name;
                document.getElementById('posSuccessTotal').textContent = result.total_formatted;
                
                document.getElementById('posSuccessWhatsappBtn').href = result.whatsapp_url;
                document.getElementById('posSuccessPdfBtn').href = result.pdf_url;
                document.getElementById('posSuccessShowBtn').href = result.show_url;

                successModal.style.display = 'flex';
            } else {
                let errorMsg = result.message || 'Error al procesar la orden.';
                if (result.errors) {
                    errorMsg += '\n' + Object.values(result.errors).flat().join('\n');
                }
                alert(errorMsg);
            }
        } catch (error) {
            console.error(error);
            alert('Ocurrió un error inesperado de conexión al guardar la orden.');
        } finally {
            submitBtn.disabled = false;
            submitBtnText.textContent = originalText;
        }
    });

    // Reset for New Sale
    window.resetPosForNewSale = function() {
        successModal.style.display = 'none';
        cart = [];
        renderCart();
        orderForm.reset();
        document.getElementById('posDeliveryMethod').value = 'venta_directa';
        document.getElementById('posDeliveryCity').value = 'Caracas';
        document.getElementById('posDeliveryAddress').value = 'Venta directa en local';
        document.getElementById('posOrderStatus').value = 'Confirmada';
        document.getElementById('posDiscountAmount').value = '0.00';
        document.getElementById('posShippingFee').value = '0.00';
        if (giftFields) giftFields.style.display = 'none';
        recalculateTotals();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
});
</script>
@endsection
