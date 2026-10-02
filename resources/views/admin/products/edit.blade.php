@extends('layouts.admin')

@section('page_title', 'Editar Producto: ' . $product->name)

@section('admin_content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Editar Producto: {{ $product->name }}</h2>
            <span style="font-size: var(--text-xs); color: var(--color-text-muted);">SKU: {{ $product->sku }} | Slug: {{ $product->slug }}</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('product', $product->slug) }}" target="_blank" class="btn btn-outline btn-sm">Ver en Tienda</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">&larr; Volver al Listado</a>
        </div>
    </div>

    @if($errors->any())
        <div style="background-color: var(--color-error-bg); border-left: 4px solid var(--color-error); padding: 12px; border-radius: var(--radius-xs); margin-bottom: 20px; color: var(--color-error); font-size: 0.85rem;">
            <strong>Errores al actualizar:</strong>
            <ul style="margin-top: 5px; list-style: disc; padding-left: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productEditForm" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="product-form-layout">
            <!-- Main Details -->
            <div class="product-form-main">
                <div class="form-group">
                    <label class="form-label" for="name">Nombre del Producto *</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                    <div class="form-group">
                        <label class="form-label" for="category_id">Categoría *</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">Seleccionar Categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="target_audience">Línea / Público *</label>
                        <select id="target_audience" name="target_audience" class="form-select" required>
                            <option value="all" {{ old('target_audience', $product->target_audience) === 'all' ? 'selected' : '' }}>Todo Público (General)</option>
                            <option value="moraia" {{ old('target_audience', $product->target_audience) === 'moraia' ? 'selected' : '' }}>Línea Moraia (16+)</option>
                            <option value="moraia_intimo" {{ old('target_audience', $product->target_audience) === 'moraia_intimo' ? 'selected' : '' }}>Línea Moraia Íntimo (18+)</option>
                        </select>
                    </div>
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr 1fr; gap: var(--space-4);">
                    <div class="form-group">
                        <label class="form-label" for="price">Precio ($ USD) *</label>
                        <input type="number" step="0.01" min="0" id="price" name="price" class="form-input" value="{{ old('price', $product->price) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="compare_at_price">Precio de Oferta / Antes</label>
                        <input type="number" step="0.01" min="0" id="compare_at_price" name="compare_at_price" class="form-input" value="{{ old('compare_at_price', $product->compare_at_price) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="stock_quantity">Stock General *</label>
                        <input type="number" min="0" id="stock_quantity" name="stock_quantity" class="form-input" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="short_description">Descripción Corta</label>
                    <textarea id="short_description" name="short_description" class="form-textarea" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Descripción Completa & Cuidados</label>
                    <textarea id="description" name="description" class="form-textarea" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Dynamic Product Variants & Options Manager -->
                <div class="variants-panel">
                    <div class="variants-header">
                        <div class="variants-title-wrap">
                            <h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: var(--color-primary-dark);">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                </svg>
                                Variantes Dinámicas & Control de Stock
                            </h3>
                            <p>Define atributos personalizados (Tallas, Colores, Telas, etc.) y gestiona el stock específico de cada combinación.</p>
                        </div>
                    </div>

                    <!-- Mode Tabs -->
                    <div class="variant-mode-tabs">
                        <button type="button" class="variant-mode-tab active" data-tab="matrixTab">
                            ✨ Atributos & Matriz Combinada
                        </button>
                        <button type="button" class="variant-mode-tab" data-tab="customizationsTab">
                            🎁 Personalizaciones & Add-ons (Opcional)
                        </button>
                    </div>

                    <!-- Hidden Config Inputs for JSON Attributes & Customizations -->
                    <input type="hidden" id="optionsConfigInput" name="options_config" value="{{ json_encode(old('options_config', $product->options_config ?? [])) }}">
                    <input type="hidden" id="customizationsConfigInput" name="customizations_config" value="{{ json_encode(old('customizations_config', $product->customizations_config ?? [])) }}">

                    <!-- TAB 1: Attributes & Matrix Generator -->
                    <div id="matrixTab" class="variant-tab-content active">
                        <!-- 1. Attributes Builder Box -->
                        <div class="attributes-builder-box">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                                <div>
                                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-text); text-transform: uppercase;">
                                        1. Definir Atributos del Producto
                                    </span>
                                    <p style="font-size: 0.72rem; color: var(--color-text-muted); margin: 2px 0 0 0;">
                                        Crea opciones como Talla, Color, Tipo de Tela, etc. con sus valores.
                                    </p>
                                </div>
                                <div style="display: flex; gap: 6px;">
                                    <button type="button" id="btnAddAttribute" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.35rem 0.75rem;">
                                        + Agregar Atributo
                                    </button>
                                </div>
                            </div>

                            <!-- Fast Presets Banner -->
                            <div class="variant-preset-box" style="margin-bottom: 1rem;">
                                <span class="variant-preset-label">Plantillas Rápidas:</span>
                                <button type="button" class="variant-chip-btn" onclick="applyAttributeTemplate('ropa')">+ Tallas Ropa (S, M, L, XL)</button>
                                <button type="button" class="variant-chip-btn" onclick="applyAttributeTemplate('lenceria')">+ Tallas Lencería (32B, 34B, 36B, 38B)</button>
                                <button type="button" class="variant-chip-btn" onclick="applyAttributeTemplate('colores')">+ Paleta de Colores Moraia</button>
                                <button type="button" class="variant-chip-btn" onclick="applyAttributeTemplate('telas')">+ Tipos de Tela</button>
                            </div>

                            <!-- Attributes List Container -->
                            <div id="attributesContainer" class="attributes-list">
                                <!-- Dynamic attribute rows inserted via JS -->
                            </div>

                            <!-- Generator Action Banner -->
                            <div class="matrix-generator-banner">
                                <div class="matrix-generator-info">
                                    <span id="matrixCombinationsCount">0 atributos definidos</span>
                                    <div style="font-size: 0.7rem; color: var(--color-text-muted);">
                                        Genera la lista completa con stock individual por combinación (ej: Talla S / Rojo = 15 uds).
                                    </div>
                                </div>
                                <button type="button" id="btnGenerateMatrix" class="btn btn-primary btn-sm" style="font-weight: 700; padding: 0.45rem 1rem;">
                                    ⚡ Generar / Reconstruir Matriz
                                </button>
                            </div>
                        </div>

                        <!-- 2. Stock Matrix Table -->
                        <div style="margin-top: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                                <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-text); text-transform: uppercase;">
                                    2. Matriz de Variantes & Stock Individual
                                </span>
                                <button type="button" id="btnAddManualRow" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.35rem 0.65rem;">
                                    + Agregar Fila Manual
                                </button>
                            </div>

                            <!-- Bulk Toolbar -->
                            <div class="matrix-bulk-toolbar">
                                <div class="matrix-bulk-actions">
                                    <span style="font-weight: 600; color: var(--color-text-muted);">Asignar en lote:</span>
                                    <span>Stock:</span>
                                    <input type="number" id="bulkStockInput" class="form-input matrix-bulk-input" placeholder="15" value="15" min="0">
                                    <button type="button" id="btnApplyBulkStock" class="btn btn-secondary btn-sm" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                                        Aplicar Stock
                                    </button>

                                    <span style="margin-left: 6px;">+ Precio ($):</span>
                                    <input type="number" step="0.01" id="bulkPriceInput" class="form-input matrix-bulk-input" placeholder="0.00" value="0.00">
                                    <button type="button" id="btnApplyBulkPrice" class="btn btn-secondary btn-sm" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                                        Aplicar Precio
                                    </button>
                                </div>
                                <div>
                                    <button type="button" id="btnClearMatrix" class="btn btn-outline btn-sm" style="font-size: 0.7rem; padding: 0.25rem 0.5rem; color: #C05C5C;">
                                        Limpiar Matriz
                                    </button>
                                </div>
                            </div>

                            <!-- Matrix Table Container -->
                            <div class="variants-table-wrap">
                                <table class="variants-table" id="matrixVariantsTable">
                                    <thead>
                                        <tr>
                                            <th>Combinación / Atributos</th>
                                            <th style="width: 130px;">SKU Específico</th>
                                            <th style="width: 120px;">+ Precio ($ USD)</th>
                                            <th style="width: 110px;">Stock Individual *</th>
                                            <th style="width: 70px; text-align: center;">Activa</th>
                                            <th style="width: 50px; text-align: center;">Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="matrixTableBody">
                                        <!-- Matrix rows generated dynamically -->
                                    </tbody>
                                </table>
                                <div id="noMatrixNotice" class="variants-empty-notice" style="display: none;">
                                    Aún no has generado la matriz de combinaciones. Agrega atributos arriba y haz clic en <strong>"⚡ Generar Matriz de Combinaciones"</strong>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Customizations & Add-ons (Single or Multiple Choice) -->
                    <div id="customizationsTab" class="variant-tab-content" style="display: none;">
                        <div class="attributes-builder-box">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                                <div>
                                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-text); text-transform: uppercase;">
                                        Opciones Adicionales y Personalizaciones
                                    </span>
                                    <p style="font-size: 0.72rem; color: var(--color-text-muted); margin: 2px 0 0 0;">
                                        Permite al cliente elegir personalizaciones como dedicatorias, envolturas o complementos con costo adicional.
                                    </p>
                                </div>
                                <button type="button" id="btnAddCustomizationGroup" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.35rem 0.75rem;">
                                    + Agregar Grupo de Personalización
                                </button>
                            </div>

                            <div id="customizationsContainer">
                                <!-- Dynamic customization cards inserted via JS -->
                            </div>

                            <div id="noCustomizationsNotice" class="variants-empty-notice" style="display: none;">
                                No hay opciones de personalización configuradas. Haz clic en <strong>"+ Agregar Grupo de Personalización"</strong> si deseas ofrecer opciones de selección única (dropdown) o selección múltiple (checkboxes).
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-top: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-4);">
                        Optimización SEO
                    </h3>
                    <div class="form-group">
                        <label class="form-label" for="seo_title">Título SEO</label>
                        <input type="text" id="seo_title" name="seo_title" class="form-input" value="{{ old('seo_title', $product->seo_title) }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="seo_description">Meta Descripción SEO</label>
                        <textarea id="seo_description" name="seo_description" class="form-textarea" rows="2">{{ old('seo_description', $product->seo_description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar Controls & Gallery Management -->
            <div class="product-form-sidebar">
                <!-- Interactive Dropzone & Sortable Gallery -->
                <div style="background-color: #FFFFFF; padding: var(--space-5); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-3); display: flex; align-items: center; justify-content: space-between;">
                        <span>Galería de Fotografías</span>
                        <span style="font-size: 0.7rem; color: var(--color-primary-dark); font-weight: 600;">📁 uploads/products</span>
                    </h3>

                    <!-- Hidden file inputs & tracking containers -->
                    <input type="file" id="images_input" name="images[]" multiple accept="image/*" style="display: none;">
                    <input type="hidden" id="image_order" name="image_order" value="">
                    <div id="deletedImagesContainer"></div>

                    <!-- Drag and drop zone box -->
                    <div id="dropzoneBox" class="dropzone-upload-box">
                        <div class="dropzone-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <div class="dropzone-title">Arrastra y suelta más fotos aquí</div>
                        <div class="dropzone-subtitle">o haz clic para explorar tus archivos</div>
                        <div class="dropzone-specs">📐 Formato 1:1 Cuadrado (500x500 px recomendado)</div>
                    </div>

                    <!-- Sortable Gallery Grid -->
                    <div class="sortable-gallery-section" style="margin-top: 1.25rem;">
                        <div class="sortable-gallery-header">
                            <div class="sortable-gallery-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                Orden de Imágenes
                            </div>
                            <span class="sortable-gallery-hint">Arrastra para ordenar • 1ª es portada</span>
                        </div>

                        <div id="sortableGrid" class="sortable-gallery-grid">
                            @foreach($product->images as $index => $img)
                                <div class="sortable-image-card" data-id="existing_{{ $img->id }}" data-existing-id="{{ $img->id }}">
                                    <img src="{{ asset($img->image_path) }}" alt="{{ $product->name }}">
                                    <span class="card-badge-cover" style="{{ $img->is_cover || $index === 0 ? 'display: inline-block;' : '' }}">★ Portada</span>
                                    <span class="card-badge-order">#{{ $index + 1 }}</span>
                                    <button type="button" class="card-btn-delete" title="Eliminar Fotografía">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Identifiers & Badges -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-5); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-3);">
                        Estado & Visibilidad
                    </h3>

                    <div class="form-group">
                        <label class="form-label" for="sku">Código SKU Base</label>
                        <input type="text" id="sku" name="sku" class="form-input" value="{{ old('sku', $product->sku) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="badge">Badge / Etiqueta Visual</label>
                        <input type="text" id="badge" name="badge" class="form-input" value="{{ old('badge', $product->badge) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span><strong>Producto Activo</strong> (Visible en tienda)</span>
                        </label>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-check">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <span><strong>Producto Destacado</strong> (Aparece en portada)</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block" style="padding: 0.85rem 1.5rem; font-size: var(--text-sm); font-weight: 700; letter-spacing: var(--tracking-wide); text-transform: uppercase;">
                    Actualizar Producto
                </button>
            </div>
        </div>

        <!-- Floating / Sticky Actions Bar (Always visible while scrolling) -->
        <div class="admin-sticky-actions">
            <div class="admin-sticky-actions-info">
                <span class="admin-sticky-actions-title">Editando: {{ Str::limit($product->name, 35) }}</span>
                <span class="admin-sticky-actions-badge">{{ $product->sku ?: 'Sin SKU' }}</span>
            </div>
            <div class="admin-sticky-actions-btns">
                <a href="{{ route('admin.products.index') }}" class="btn-cancel-floating">
                    &larr; Volver
                </a>
                <button type="submit" class="btn-save-floating">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span>Actualizar Producto</span>
                </button>
            </div>
        </div>

        <div style="height: 65px;"></div>
    </form>
</div>
@endsection

@push('scripts')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ------------------------------------------------------------------------
    // 1. TAB NAVIGATION (Matrix vs Customizations)
    // ------------------------------------------------------------------------
    const tabButtons = document.querySelectorAll('.variant-mode-tab');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            tabButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const targetId = this.getAttribute('data-tab');
            document.querySelectorAll('.variant-tab-content').forEach(c => c.style.display = 'none');
            const targetEl = document.getElementById(targetId);
            if (targetEl) targetEl.style.display = 'block';
        });
    });

    // ------------------------------------------------------------------------
    // 2. DYNAMIC ATTRIBUTES BUILDER STATE & LOGIC
    // ------------------------------------------------------------------------
    let attributes = [];
    let attrIdCounter = 1;
    let matrixRowIndex = 0;

    const attributesContainer = document.getElementById('attributesContainer');
    const optionsConfigInput = document.getElementById('optionsConfigInput');
    const matrixCountEl = document.getElementById('matrixCombinationsCount');
    const matrixTbody = document.getElementById('matrixTableBody');
    const noMatrixNotice = document.getElementById('noMatrixNotice');

    function renderAttributes() {
        attributesContainer.innerHTML = '';
        if (attributes.length === 0) {
            attributesContainer.innerHTML = `
                <div style="text-align: center; padding: 1.5rem; color: var(--color-text-muted); font-size: 0.75rem; background: #FAF6F4; border-radius: var(--radius-xs); border: 1px dashed var(--color-border);">
                    No hay atributos definidos. Usa los botones superiores o plantillas para comenzar (ej: Talla, Color).
                </div>
            `;
        }

        attributes.forEach((attr) => {
            const card = document.createElement('div');
            card.className = 'attribute-row-card';
            card.innerHTML = `
                <div>
                    <label style="font-size: 0.7rem; font-weight: 700; color: var(--color-text-muted); text-transform: uppercase; display: block; margin-bottom: 3px;">Nombre del Atributo</label>
                    <input type="text" class="form-input attr-name-input" value="${escapeHtml(attr.name)}" placeholder="Ej: Talla, Color, Tela..." style="font-size: 0.8rem; font-weight: 700;">
                </div>
                <div>
                    <label style="font-size: 0.7rem; font-weight: 700; color: var(--color-text-muted); text-transform: uppercase; display: block; margin-bottom: 3px;">Valores / Opciones (Escribe y presiona Enter)</label>
                    <div class="attribute-values-chips-wrap" data-attr-id="${attr.id}">
                        ${attr.values.map((v, vIdx) => `
                            <span class="attr-value-chip">
                                ${escapeHtml(v)}
                                <span class="chip-del" data-attr-id="${attr.id}" data-val-idx="${vIdx}" title="Eliminar">&times;</span>
                            </span>
                        `).join('')}
                        <input type="text" class="attr-value-input" placeholder="+ Agregar valor..." data-attr-id="${attr.id}">
                    </div>
                </div>
                <div style="text-align: right;">
                    <button type="button" class="btn-remove-variant-row" data-remove-attr="${attr.id}" title="Eliminar atributo">✕</button>
                </div>
            `;

            // Bind Name Input
            card.querySelector('.attr-name-input').addEventListener('input', function() {
                attr.name = this.value;
                updateCombinationsCount();
                syncOptionsConfig();
            });

            // Bind Chip Delete
            card.querySelectorAll('.chip-del').forEach(delBtn => {
                delBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const aId = parseInt(this.getAttribute('data-attr-id'));
                    const vIdx = parseInt(this.getAttribute('data-val-idx'));
                    const targetAttr = attributes.find(a => a.id === aId);
                    if (targetAttr) {
                        targetAttr.values.splice(vIdx, 1);
                        renderAttributes();
                        updateCombinationsCount();
                        syncOptionsConfig();
                    }
                });
            });

            // Bind Value Chip Input
            const valueInput = card.querySelector('.attr-value-input');
            valueInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addValueToAttr(attr.id, this.value);
                    this.value = '';
                }
            });

            // Bind Delete Attribute
            card.querySelector('[data-remove-attr]').addEventListener('click', function() {
                attributes = attributes.filter(a => a.id !== attr.id);
                renderAttributes();
                updateCombinationsCount();
                syncOptionsConfig();
            });

            attributesContainer.appendChild(card);
        });

        updateCombinationsCount();
        syncOptionsConfig();
    }

    function addValueToAttr(attrId, value) {
        const valClean = value.trim().replace(/^,|,$/g, '');
        if (!valClean) return;
        const attr = attributes.find(a => a.id === attrId);
        if (attr && !attr.values.includes(valClean)) {
            attr.values.push(valClean);
            renderAttributes();
            setTimeout(() => {
                const el = document.querySelector(`.attribute-values-chips-wrap[data-attr-id="${attrId}"] .attr-value-input`);
                if (el) el.focus();
            }, 50);
        }
    }

    function addAttribute(name = 'Nuevo Atributo', values = []) {
        attributes.push({
            id: attrIdCounter++,
            name: name,
            values: Array.isArray(values) ? values : []
        });
        renderAttributes();
    }

    window.applyAttributeTemplate = function(templateType) {
        if (templateType === 'ropa') {
            const existing = attributes.find(a => a.name.toLowerCase().includes('talla'));
            if (existing) existing.values = Array.from(new Set([...existing.values, 'S', 'M', 'L', 'XL']));
            else addAttribute('Talla', ['S', 'M', 'L', 'XL']);
        } else if (templateType === 'lenceria') {
            const existing = attributes.find(a => a.name.toLowerCase().includes('talla') || a.name.toLowerCase().includes('copa'));
            if (existing) existing.values = Array.from(new Set([...existing.values, '32B', '34B', '36B', '38B']));
            else addAttribute('Talla de Copa', ['32B', '34B', '36B', '38B']);
        } else if (templateType === 'colores') {
            const existing = attributes.find(a => a.name.toLowerCase().includes('color'));
            if (existing) existing.values = Array.from(new Set([...existing.values, 'Rosa Mauve', 'Negro Noche', 'Blanco Seda', 'Vino Tinto']));
            else addAttribute('Color', ['Rosa Mauve', 'Negro Noche', 'Blanco Seda', 'Vino Tinto']);
        } else if (templateType === 'telas') {
            const existing = attributes.find(a => a.name.toLowerCase().includes('tela'));
            if (existing) existing.values = Array.from(new Set([...existing.values, 'Seda Satén', 'Algodón Pima', 'Encaje Francés']));
            else addAttribute('Tipo de Tela', ['Seda Satén', 'Algodón Pima', 'Encaje Francés']);
        }
    };

    function updateCombinationsCount() {
        const validAttrs = attributes.filter(a => a.name.trim() !== '' && a.values.length > 0);
        if (validAttrs.length === 0) {
            matrixCountEl.innerHTML = '0 atributos configurados';
            return;
        }
        const total = validAttrs.reduce((acc, curr) => acc * curr.values.length, 1);
        matrixCountEl.innerHTML = `<strong>${validAttrs.length} atributos</strong> (${validAttrs.map(a => a.name).join(', ')}) &bull; <strong>${total} combinaciones posibles</strong>`;
    }

    function syncOptionsConfig() {
        const validAttrs = attributes
            .filter(a => a.name.trim() !== '' && a.values.length > 0)
            .map(a => ({ name: a.name.trim(), values: a.values }));
        optionsConfigInput.value = validAttrs.length > 0 ? JSON.stringify(validAttrs) : '';
    }

    document.getElementById('btnAddAttribute')?.addEventListener('click', () => {
        addAttribute('Nueva Opción', []);
    });

    // ------------------------------------------------------------------------
    // 3. CARTESIAN PRODUCT & COMBINATION MATRIX GENERATOR
    // ------------------------------------------------------------------------
    function cartesianProduct(arr) {
        return arr.reduce((a, b) => {
            return a.flatMap(d => b.map(e => [d, e].flat()));
        });
    }

    function generateMatrixCombinations() {
        const validAttrs = attributes.filter(a => a.name.trim() !== '' && a.values.length > 0);
        if (validAttrs.length === 0) {
            alert('Por favor agrega al menos un atributo con valores (ej: Talla con S, M, L) antes de generar la matriz.');
            return;
        }

        const existingRowMap = {};
        matrixTbody.querySelectorAll('tr').forEach(tr => {
            const comboKey = tr.getAttribute('data-combo-key');
            if (comboKey) {
                existingRowMap[comboKey] = {
                    id: tr.querySelector('.matrix-id-input')?.value || '',
                    stock: tr.querySelector('.matrix-stock-input')?.value || '15',
                    price: tr.querySelector('.matrix-price-input')?.value || '0.00',
                    sku: tr.querySelector('.matrix-sku-input')?.value || '',
                    active: tr.querySelector('.matrix-active-check')?.checked ?? true
                };
            }
        });

        matrixTbody.innerHTML = '';
        const baseSku = document.getElementById('sku')?.value.trim() || 'MOR';
        const attrValuesMatrix = validAttrs.map(a => a.values.map(v => ({ attr: a.name.trim(), val: v })));

        let combinations = [];
        if (attrValuesMatrix.length === 1) {
            combinations = attrValuesMatrix[0].map(item => [item]);
        } else {
            combinations = cartesianProduct(attrValuesMatrix);
        }

        combinations.forEach(combo => {
            const optionsObj = {};
            const valParts = [];
            const badgeChips = [];

            combo.forEach(item => {
                optionsObj[item.attr] = item.val;
                valParts.push(item.val);
                badgeChips.push(`<span class="variant-combination-badge"><strong>${escapeHtml(item.attr)}:</strong> ${escapeHtml(item.val)}</span>`);
            });

            const comboName = valParts.join(' / ');
            const comboKey = valParts.join('__');
            const autoSku = baseSku + '-' + valParts.map(p => p.substring(0, 3).toUpperCase().replace(/[^A-Z0-9]/g, '')).join('-');

            const existing = existingRowMap[comboKey];
            const variantId = existing ? existing.id : '';
            const stockVal = existing ? existing.stock : (document.getElementById('bulkStockInput')?.value || '15');
            const priceVal = existing ? existing.price : (document.getElementById('bulkPriceInput')?.value || '0.00');
            const skuVal = existing ? existing.sku : autoSku;
            const activeVal = existing ? existing.active : true;

            appendMatrixRow({
                id: variantId,
                comboKey: comboKey,
                name: comboName,
                optionsJson: JSON.stringify(optionsObj),
                badgeHtml: badgeChips.join(' '),
                sku: skuVal,
                priceModifier: priceVal,
                stock: stockVal,
                isActive: activeVal,
                variantType: 'combinacion'
            });
        });

        if (noMatrixNotice) noMatrixNotice.style.display = 'none';
    }

    function appendMatrixRow(data) {
        const idx = matrixRowIndex++;
        const tr = document.createElement('tr');
        tr.setAttribute('data-combo-key', data.comboKey || ('manual_' + idx));
        tr.innerHTML = `
            <td>
                ${data.id ? `<input type="hidden" name="variants[${idx}][id]" class="matrix-id-input" value="${data.id}">` : ''}
                <input type="hidden" name="variants[${idx}][variant_type]" value="${escapeHtml(data.variantType || 'combinacion')}">
                <input type="hidden" name="variants[${idx}][options]" value="${escapeHtml(data.optionsJson || '{}')}">
                <input type="hidden" name="variants[${idx}][value]" value="${escapeHtml(data.name)}">
                <div style="margin-bottom: 4px;">
                    ${data.badgeHtml || `<span class="variant-combination-badge">${escapeHtml(data.name)}</span>`}
                </div>
                <input type="text" name="variants[${idx}][name]" class="form-input" value="${escapeHtml(data.name)}" style="font-size: 0.75rem; font-weight: 600; padding: 2px 6px;" placeholder="Nombre de combinación" required>
            </td>
            <td>
                <input type="text" name="variants[${idx}][sku]" class="form-input matrix-sku-input" value="${escapeHtml(data.sku || '')}" placeholder="SKU...">
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="variants[${idx}][price_modifier]" class="form-input matrix-price-input" value="${escapeHtml(data.priceModifier || '0.00')}" placeholder="0.00">
            </td>
            <td>
                <input type="number" min="0" name="variants[${idx}][stock_quantity]" class="form-input matrix-stock-input" value="${escapeHtml(data.stock || '15')}" style="font-weight: 700; color: var(--color-primary-dark);" required>
            </td>
            <td style="text-align: center;">
                <input type="hidden" name="variants[${idx}][is_active]" value="0">
                <input type="checkbox" name="variants[${idx}][is_active]" class="matrix-active-check" value="1" ${data.isActive ? 'checked' : ''}>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn-remove-variant-row" onclick="this.closest('tr').remove(); checkMatrixEmpty();" title="Eliminar variante">✕</button>
            </td>
        `;

        matrixTbody.appendChild(tr);
        if (noMatrixNotice) noMatrixNotice.style.display = 'none';
    }

    window.checkMatrixEmpty = function() {
        if (matrixTbody.children.length === 0 && noMatrixNotice) {
            noMatrixNotice.style.display = 'block';
        }
    };

    document.getElementById('btnGenerateMatrix')?.addEventListener('click', generateMatrixCombinations);

    document.getElementById('btnAddManualRow')?.addEventListener('click', () => {
        appendMatrixRow({
            name: 'Variante Personalizada',
            optionsJson: '{}',
            badgeHtml: '<span class="variant-combination-badge">Manual</span>',
            sku: '',
            priceModifier: '0.00',
            stock: '15',
            isActive: true,
            variantType: 'combinacion'
        });
    });

    document.getElementById('btnApplyBulkStock')?.addEventListener('click', function() {
        const val = document.getElementById('bulkStockInput')?.value || '15';
        matrixTbody.querySelectorAll('.matrix-stock-input').forEach(inp => inp.value = val);
    });

    document.getElementById('btnApplyBulkPrice')?.addEventListener('click', function() {
        const val = document.getElementById('bulkPriceInput')?.value || '0.00';
        matrixTbody.querySelectorAll('.matrix-price-input').forEach(inp => inp.value = val);
    });

    document.getElementById('btnClearMatrix')?.addEventListener('click', function() {
        if (confirm('¿Deseas vaciar todas las combinaciones de la matriz?')) {
            matrixTbody.innerHTML = '';
            checkMatrixEmpty();
        }
    });

    // ------------------------------------------------------------------------
    // 4. CUSTOMIZATIONS & ADD-ONS STATE & BUILDER
    // ------------------------------------------------------------------------
    let customizationGroups = [];
    const customizationsContainer = document.getElementById('customizationsContainer');
    const customizationsConfigInput = document.getElementById('customizationsConfigInput');
    const noCustomizationsNotice = document.getElementById('noCustomizationsNotice');

    function renderCustomizations() {
        customizationsContainer.innerHTML = '';
        if (customizationGroups.length === 0) {
            if (noCustomizationsNotice) noCustomizationsNotice.style.display = 'block';
            customizationsConfigInput.value = '';
            return;
        }

        if (noCustomizationsNotice) noCustomizationsNotice.style.display = 'none';

        customizationGroups.forEach((group, gIdx) => {
            const card = document.createElement('div');
            card.className = 'customization-item-card';
            card.innerHTML = `
                <div class="customization-item-header">
                    <div style="font-weight: 700; font-size: 0.8rem; color: var(--color-primary-dark);">
                        Grupo de Opción #${gIdx + 1}
                    </div>
                    <button type="button" class="btn-remove-variant-row" data-remove-group="${gIdx}" title="Eliminar grupo">✕</button>
                </div>
                <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div>
                        <label style="font-size: 0.7rem; font-weight: 700; color: var(--color-text-muted);">Título de la Opción (ej: Envoltura de Regalo, Dedicatoria)</label>
                        <input type="text" class="form-input custom-group-title" value="${escapeHtml(group.title)}" placeholder="Ej: Envoltura de Regalo">
                    </div>
                    <div>
                        <label style="font-size: 0.7rem; font-weight: 700; color: var(--color-text-muted);">Tipo de Selección</label>
                        <select class="form-select custom-group-type">
                            <option value="single" ${group.selectionType === 'single' ? 'selected' : ''}>🔘 Selección Única (Dropdown / Radio)</option>
                            <option value="multiple" ${group.selectionType === 'multiple' ? 'selected' : ''}>☑️ Selección Múltiple (Checkboxes)</option>
                        </select>
                    </div>
                </div>

                <div style="background: #FAF8F6; padding: 0.65rem 0.85rem; border-radius: var(--radius-xs); border: 1px solid var(--color-border-light);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--color-text);">Valores y Costo Extra</span>
                        <button type="button" class="btn btn-outline btn-sm" data-add-option-val="${gIdx}" style="font-size: 0.68rem; padding: 0.2rem 0.5rem;">+ Agregar Valor</button>
                    </div>
                    <div class="custom-group-values-list" data-gidx="${gIdx}">
                        ${group.options.map((opt, oIdx) => `
                            <div style="display: flex; gap: 6px; align-items: center; margin-bottom: 4px;">
                                <input type="text" class="form-input opt-val-label" data-gidx="${gIdx}" data-oidx="${oIdx}" value="${escapeHtml(opt.label)}" placeholder="Nombre de opción (ej: Caja de Lujo)" style="flex: 2; font-size: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 2px;">
                                    <span style="font-size: 0.7rem; color: var(--color-text-muted);">+$</span>
                                    <input type="number" step="0.01" min="0" class="form-input opt-val-price" data-gidx="${gIdx}" data-oidx="${oIdx}" value="${opt.price}" placeholder="0.00" style="width: 75px; font-size: 0.75rem;">
                                </div>
                                <button type="button" class="btn-remove-variant-row" data-remove-opt="${gIdx}_${oIdx}" style="width: 22px; height: 22px; font-size: 0.65rem;">✕</button>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;

            // Bind Title
            card.querySelector('.custom-group-title').addEventListener('input', function() {
                group.title = this.value;
                syncCustomizationsConfig();
            });

            // Bind Selection Type
            card.querySelector('.custom-group-type').addEventListener('change', function() {
                group.selectionType = this.value;
                syncCustomizationsConfig();
            });

            // Bind Value Inputs
            card.querySelectorAll('.opt-val-label').forEach(inp => {
                inp.addEventListener('input', function() {
                    const g = parseInt(this.getAttribute('data-gidx'));
                    const o = parseInt(this.getAttribute('data-oidx'));
                    customizationGroups[g].options[o].label = this.value;
                    syncCustomizationsConfig();
                });
            });

            card.querySelectorAll('.opt-val-price').forEach(inp => {
                inp.addEventListener('input', function() {
                    const g = parseInt(this.getAttribute('data-gidx'));
                    const o = parseInt(this.getAttribute('data-oidx'));
                    customizationGroups[g].options[o].price = this.value;
                    syncCustomizationsConfig();
                });
            });

            // Bind Add Option Value
            card.querySelector(`[data-add-option-val="${gIdx}"]`).addEventListener('click', () => {
                group.options.push({ label: 'Opción Nueva', price: '0.00' });
                renderCustomizations();
                syncCustomizationsConfig();
            });

            // Bind Remove Option Value
            card.querySelectorAll('[data-remove-opt]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const [g, o] = this.getAttribute('data-remove-opt').split('_').map(n => parseInt(n));
                    customizationGroups[g].options.splice(o, 1);
                    renderCustomizations();
                    syncCustomizationsConfig();
                });
            });

            // Bind Remove Group
            card.querySelector(`[data-remove-group="${gIdx}"]`).addEventListener('click', () => {
                customizationGroups.splice(gIdx, 1);
                renderCustomizations();
                syncCustomizationsConfig();
            });

            customizationsContainer.appendChild(card);
        });

        syncCustomizationsConfig();
    }

    function syncCustomizationsConfig() {
        const validGroups = customizationGroups.filter(g => g.title.trim() !== '' && g.options.length > 0);
        customizationsConfigInput.value = validGroups.length > 0 ? JSON.stringify(validGroups) : '';
    }

    document.getElementById('btnAddCustomizationGroup')?.addEventListener('click', () => {
        customizationGroups.push({
            title: 'Personalización',
            selectionType: 'single',
            options: [
                { label: 'Estándar', price: '0.00' },
                { label: 'Premium / Personalizado', price: '2.50' }
            ]
        });
        renderCustomizations();
    });

    // ------------------------------------------------------------------------
    // 5. REHYDRATE EXISTING DATA FROM BACKEND
    // ------------------------------------------------------------------------
    try {
        const initialAttrs = @json($product->options_config ?? []);
        if (Array.isArray(initialAttrs) && initialAttrs.length > 0) {
            attributes = initialAttrs.map(a => ({
                id: attrIdCounter++,
                name: a.name || '',
                values: Array.isArray(a.values) ? a.values : []
            }));
            renderAttributes();
        }
    } catch (e) {
        console.error('Error rehydrating options_config', e);
    }

    try {
        const initialCustoms = @json($product->customizations_config ?? []);
        if (Array.isArray(initialCustoms) && initialCustoms.length > 0) {
            customizationGroups = initialCustoms.map(g => ({
                title: g.title || 'Personalización',
                selectionType: g.selectionType || 'single',
                options: Array.isArray(g.options) ? g.options : []
            }));
            renderCustomizations();
        }
    } catch (e) {
        console.error('Error rehydrating customizations_config', e);
    }

    // Rehydrate Existing Variants into the Matrix Table
    const initialVariants = @json($product->allVariants ?? []);
    if (Array.isArray(initialVariants) && initialVariants.length > 0) {
        initialVariants.forEach(v => {
            let optionsObj = {};
            let badgeChips = [];
            let comboKey = v.name;

            if (v.options && typeof v.options === 'object') {
                optionsObj = v.options;
                const keys = Object.keys(v.options);
                if (keys.length > 0) {
                    comboKey = Object.values(v.options).join('__');
                    keys.forEach(k => {
                        badgeChips.push(`<span class="variant-combination-badge"><strong>${escapeHtml(k)}:</strong> ${escapeHtml(v.options[k])}</span>`);
                    });
                }
            }

            if (badgeChips.length === 0) {
                badgeChips.push(`<span class="variant-combination-badge">${escapeHtml(v.variant_type ? v.variant_type.toUpperCase() + ': ' : '')}${escapeHtml(v.name)}</span>`);
            }

            appendMatrixRow({
                id: v.id,
                comboKey: comboKey,
                name: v.name,
                optionsJson: JSON.stringify(optionsObj),
                badgeHtml: badgeChips.join(' '),
                sku: v.sku || '',
                priceModifier: parseFloat(v.price_modifier || 0).toFixed(2),
                stock: v.stock_quantity ?? 15,
                isActive: v.is_active == 1,
                variantType: v.variant_type || 'combinacion'
            });
        });
    } else {
        if (noMatrixNotice) noMatrixNotice.style.display = 'block';
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ------------------------------------------------------------------------
    // 6. GALLERY & DROPZONE SCRIPT
    // ------------------------------------------------------------------------
    const dropzoneBox = document.getElementById('dropzoneBox');
    const imagesInput = document.getElementById('images_input');
    const sortableGrid = document.getElementById('sortableGrid');
    const imageOrderInput = document.getElementById('image_order');
    const deletedImagesContainer = document.getElementById('deletedImagesContainer');
    const productEditForm = document.getElementById('productEditForm');

    let newFilesRegistry = [];
    let newFileCounter = 0;

    dropzoneBox.addEventListener('click', () => imagesInput.click());

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzoneBox.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzoneBox.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzoneBox.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzoneBox.classList.remove('dragover');
        });
    });

    dropzoneBox.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        if (dt && dt.files && dt.files.length > 0) {
            handleIncomingFiles(dt.files);
        }
    });

    imagesInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files.length > 0) {
            handleIncomingFiles(e.target.files);
        }
    });

    sortableGrid.querySelectorAll('.sortable-image-card').forEach(card => {
        const delBtn = card.querySelector('.card-btn-delete');
        if (delBtn) {
            delBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                handleCardDeletion(card);
            });
        }
    });

    function handleIncomingFiles(files) {
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const uniqueId = 'new_' + newFileCounter++;
            const previewUrl = URL.createObjectURL(file);
            newFilesRegistry.push({
                id: uniqueId,
                file: file,
                previewUrl: previewUrl
            });
            appendNewImageCard(uniqueId, previewUrl, file.name);
        });

        updateOrderBadges();
        syncDataTransfer();
    }

    function appendNewImageCard(id, url, title) {
        const card = document.createElement('div');
        card.className = 'sortable-image-card';
        card.setAttribute('data-id', id);
        card.innerHTML = `
            <img src="${url}" alt="${title}">
            <span class="card-badge-cover">★ Portada</span>
            <span class="card-badge-order">#</span>
            <span class="card-tag-new">Nueva</span>
            <button type="button" class="card-btn-delete" title="Eliminar Fotografía">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        card.querySelector('.card-btn-delete').addEventListener('click', (e) => {
            e.stopPropagation();
            handleCardDeletion(card);
        });

        sortableGrid.appendChild(card);
    }

    function handleCardDeletion(card) {
        const id = card.getAttribute('data-id');
        const existingId = card.getAttribute('data-existing-id');

        if (existingId) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deleted_image_ids[]';
            hiddenInput.value = existingId;
            deletedImagesContainer.appendChild(hiddenInput);
        } else {
            newFilesRegistry = newFilesRegistry.filter(item => item.id !== id);
        }

        card.remove();
        updateOrderBadges();
        syncDataTransfer();
    }

    function updateOrderBadges() {
        const cards = Array.from(sortableGrid.children);
        const orderSequence = [];

        cards.forEach((card, index) => {
            const id = card.getAttribute('data-id');
            orderSequence.push(id);

            const orderBadge = card.querySelector('.card-badge-order');
            if (orderBadge) orderBadge.textContent = `#${index + 1}`;

            const coverBadge = card.querySelector('.card-badge-cover');
            if (coverBadge) {
                coverBadge.style.display = index === 0 ? 'inline-block' : 'none';
            }
        });

        imageOrderInput.value = JSON.stringify(orderSequence);
    }

    function syncDataTransfer() {
        const dt = new DataTransfer();
        const cards = Array.from(sortableGrid.children);

        cards.forEach(card => {
            const id = card.getAttribute('data-id');
            const found = newFilesRegistry.find(item => item.id === id);
            if (found && found.file) {
                dt.items.add(found.file);
            }
        });

        imagesInput.files = dt.files;
    }

    new Sortable(sortableGrid, {
        animation: 180,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
            updateOrderBadges();
            syncDataTransfer();
        }
    });

    updateOrderBadges();

    productEditForm.addEventListener('submit', function() {
        updateOrderBadges();
        syncDataTransfer();
    });
});
</script>
@endpush
