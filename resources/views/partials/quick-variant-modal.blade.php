<!-- Quick Variant Selection Modal (For Shop, Home & Category Cards) -->
<div id="quick-variant-modal" class="quick-modal-backdrop" style="display: none;" aria-hidden="true">
    <div class="quick-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="quick-modal-title">
        <button type="button" class="quick-modal-close" id="quick-modal-close-btn" aria-label="Cerrar modal">&times;</button>
        
        <div class="quick-modal-body">
            <!-- Product Preview Header -->
            <div class="quick-modal-product-header">
                <div class="quick-modal-img-wrap">
                    <img id="quick-modal-img" src="" alt="Producto" loading="lazy">
                </div>
                <div class="quick-modal-info">
                    <span id="quick-modal-category" class="quick-modal-cat"></span>
                    <h3 id="quick-modal-title" class="quick-modal-title"></h3>
                    <div class="quick-modal-price-wrap">
                        <span id="quick-modal-price" class="quick-modal-price"></span>
                        <span id="quick-modal-stock-badge" class="quick-stock-badge"></span>
                    </div>
                </div>
            </div>

            <!-- Variants List -->
            <div id="quick-modal-variants-container" class="quick-modal-variants">
                <!-- Dynamically generated variant groups -->
            </div>

            <!-- Quantity & Add Button -->
            <div class="quick-modal-actions">
                <div class="quick-qty-selector">
                    <button type="button" class="quick-qty-btn" id="quick-qty-minus" aria-label="Disminuir">-</button>
                    <input type="text" id="quick-qty-input" class="quick-qty-val" value="1" readonly>
                    <button type="button" class="quick-qty-btn" id="quick-qty-plus" aria-label="Aumentar">+</button>
                </div>

                <button type="button" id="quick-modal-submit-btn" class="btn btn-primary btn-block quick-modal-add-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span>Añadir a mi Bolsa</span>
                </button>
            </div>
        </div>
    </div>
</div>
