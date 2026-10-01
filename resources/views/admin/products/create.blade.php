@extends('layouts.admin')

@section('page_title', 'Crear Nuevo Producto')

@section('admin_content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Crear Nuevo Producto</h2>
            <p style="font-size: var(--text-xs); color: var(--color-text-muted); margin-top: 2px;">
                Ingresa los datos del producto, sube fotografías y configura sus opciones de tallas y colores.
            </p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">&larr; Volver al Listado</a>
    </div>

    @if($errors->any())
        <div style="background-color: var(--color-error-bg); border-left: 4px solid var(--color-error); padding: 12px; border-radius: var(--radius-xs); margin-bottom: 20px; color: var(--color-error); font-size: 0.85rem;">
            <strong>Errores al guardar el producto:</strong>
            <ul style="margin-top: 5px; list-style: disc; padding-left: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid" style="grid-template-columns: 2fr 1.2fr; gap: var(--space-8); align-items: start;">
            <!-- Main Details -->
            <div>
                <div class="form-group">
                    <label class="form-label" for="name">Nombre del Producto *</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" placeholder="Ej: Mini Box Dulce Consentirte" required>
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                    <div class="form-group">
                        <label class="form-label" for="category_id">Categoría *</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">Seleccionar Categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="target_audience">Línea / Público *</label>
                        <select id="target_audience" name="target_audience" class="form-select" required>
                            <option value="all" {{ old('target_audience') === 'all' ? 'selected' : '' }}>Todo Público (General)</option>
                            <option value="moraia" {{ old('target_audience') === 'moraia' ? 'selected' : '' }}>Línea Moraia (16+)</option>
                            <option value="moraia_intimo" {{ old('target_audience') === 'moraia_intimo' ? 'selected' : '' }}>Línea Moraia Íntimo (18+)</option>
                        </select>
                    </div>
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr 1fr; gap: var(--space-4);">
                    <div class="form-group">
                        <label class="form-label" for="price">Precio ($ USD) *</label>
                        <input type="number" step="0.01" min="0" id="price" name="price" class="form-input" value="{{ old('price') }}" placeholder="0.00" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="compare_at_price">Precio de Oferta / Antes</label>
                        <input type="number" step="0.01" min="0" id="compare_at_price" name="compare_at_price" class="form-input" value="{{ old('compare_at_price') }}" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="stock_quantity">Stock General *</label>
                        <input type="number" min="0" id="stock_quantity" name="stock_quantity" class="form-input" value="{{ old('stock_quantity', 10) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="short_description">Descripción Corta</label>
                    <textarea id="short_description" name="short_description" class="form-textarea" rows="2" placeholder="Resumen conciso visible en tarjetas de producto...">{{ old('short_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Descripción Completa & Cuidados</label>
                    <textarea id="description" name="description" class="form-textarea" rows="4" placeholder="Detalles de confección, materiales, presentación para regalo...">{{ old('description') }}</textarea>
                </div>

                <!-- Product Variants Manager (Tallas & Colores) -->
                <div class="variants-panel">
                    <div class="variants-header">
                        <div class="variants-title-wrap">
                            <h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: var(--color-primary-dark);">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                </svg>
                                Variantes del Producto (Tallas & Colores)
                            </h3>
                            <p>Configura las opciones elegibles por el cliente en la página del producto.</p>
                        </div>
                        <div class="variant-actions-bar">
                            <button type="button" id="btnAddSize" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.35rem 0.65rem;">
                                + Agregar Talla
                            </button>
                            <button type="button" id="btnAddColor" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.35rem 0.65rem;">
                                + Agregar Color
                            </button>
                            <button type="button" id="btnAddCustom" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 0.35rem 0.65rem;">
                                + Opción Personalizada
                            </button>
                        </div>
                    </div>

                    <!-- Quick Preset Chips -->
                    <div class="variant-preset-box">
                        <span class="variant-preset-label">Atajos Rápidos:</span>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('talla', 'Talla S', 'S')">+ Talla S</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('talla', 'Talla M', 'M')">+ Talla M</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('talla', 'Talla L', 'L')">+ Talla L</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('talla', 'Talla 32B', '32B')">+ Talla 32B</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('talla', 'Talla 34B', '34B')">+ Talla 34B</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('talla', 'Talla 36B', '36B')">+ Talla 36B</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('talla', 'Talla Única', 'Única')">+ Talla Única</button>
                        <span style="color: var(--color-border); margin: 0 4px;">|</span>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('color', 'Rosa Mauve', '#D87F86')">🌸 Rosa Mauve</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('color', 'Negro Noche', '#242020')">🖤 Negro Noche</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('color', 'Blanco Seda', '#FAF5F2')">🤍 Blanco Seda</button>
                        <button type="button" class="variant-chip-btn" onclick="addPresetVariant('color', 'Vino Tinto', '#7A2028')">🍷 Vino Tinto</button>
                    </div>

                    <!-- Variants Table Container -->
                    <div class="variants-table-wrap">
                        <table class="variants-table" id="variantsTable">
                            <thead>
                                <tr>
                                    <th style="width: 130px;">Tipo</th>
                                    <th>Nombre de la Opción *</th>
                                    <th style="width: 120px;">Valor / Detalle</th>
                                    <th style="width: 120px;">+ Precio ($ USD)</th>
                                    <th style="width: 100px;">Stock</th>
                                    <th style="width: 80px; text-align: center;">Activa</th>
                                    <th style="width: 50px; text-align: center;">Quitar</th>
                                </tr>
                            </thead>
                            <tbody id="variantsTableBody">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                        <div id="noVariantsNotice" class="variants-empty-notice">
                            Este producto no tiene variantes asignadas todavía. Usa los botones superiores o atajos para agregar tallas o colores.
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-top: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-4);">
                        Optimización SEO
                    </h3>
                    <div class="form-group">
                        <label class="form-label" for="seo_title">Título SEO (Etiqueta Title)</label>
                        <input type="text" id="seo_title" name="seo_title" class="form-input" value="{{ old('seo_title') }}" placeholder="Ej: Mini Box Dulce Consentirte | Moraia">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="seo_description">Meta Descripción SEO</label>
                        <textarea id="seo_description" name="seo_description" class="form-textarea" rows="2" placeholder="Resumen atractivo para buscadores...">{{ old('seo_description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar Controls & Dropzone Gallery -->
            <div>
                <!-- Interactive Dropzone -->
                <div style="background-color: #FFFFFF; padding: var(--space-5); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-3); display: flex; align-items: center; justify-content: space-between;">
                        <span>Fotografías del Producto</span>
                        <span style="font-size: 0.7rem; color: var(--color-primary-dark); font-weight: 600;">📁 uploads/products</span>
                    </h3>

                    <!-- Hidden file input & order tracking -->
                    <input type="file" id="images_input" name="images[]" multiple accept="image/*" style="display: none;">
                    <input type="hidden" id="image_order" name="image_order" value="">

                    <!-- Drag and drop zone box -->
                    <div id="dropzoneBox" class="dropzone-upload-box">
                        <div class="dropzone-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <div class="dropzone-title">Arrastra y suelta imágenes aquí</div>
                        <div class="dropzone-subtitle">o haz clic para explorar tus archivos</div>
                        <div class="dropzone-specs">📐 Formato 1:1 Cuadrado (500x500 px recomendado)</div>
                    </div>

                    <!-- Sortable Preview Gallery -->
                    <div id="gallerySection" class="sortable-gallery-section" style="display: none;">
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
                            <!-- Image cards injected dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Identifiers & Badges -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-5); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-3);">
                        Estado & Visibilidad
                    </h3>

                    <div class="form-group">
                        <label class="form-label" for="sku">Código SKU</label>
                        <input type="text" id="sku" name="sku" class="form-input" placeholder="Ej: REG-MIN-02" value="{{ old('sku') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="badge">Badge / Etiqueta Visual</label>
                        <input type="text" id="badge" name="badge" class="form-input" placeholder="Ej: Ideal Regalo / Best Seller" value="{{ old('badge') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span><strong>Producto Activo</strong> (Visible en tienda)</span>
                        </label>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-check">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <span><strong>Producto Destacado</strong> (Aparece en portada)</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block" style="padding: 0.85rem 1.5rem; font-size: var(--text-sm); font-weight: 700; letter-spacing: var(--tracking-wide); text-transform: uppercase;">
                    Guardar Producto
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
let variantRowIndex = 0;

function addVariantRow(type = 'talla', name = '', value = '', priceMod = '0.00', stock = '10', isActive = true) {
    const tbody = document.getElementById('variantsTableBody');
    const notice = document.getElementById('noVariantsNotice');
    const idx = variantRowIndex++;

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="variants[${idx}][variant_type]" class="form-select">
                <option value="talla" ${type === 'talla' ? 'selected' : ''}>Talla</option>
                <option value="color" ${type === 'color' ? 'selected' : ''}>Color</option>
                <option value="presentacion" ${type === 'presentacion' ? 'selected' : ''}>Presentación</option>
                <option value="personalizado" ${type === 'personalizado' ? 'selected' : ''}>Personalizado</option>
            </select>
        </td>
        <td>
            <input type="text" name="variants[${idx}][name]" class="form-input" value="${name}" placeholder="Ej: Talla S o Rosa Mauve" required>
        </td>
        <td>
            <input type="text" name="variants[${idx}][value]" class="form-input" value="${value}" placeholder="Ej: S o #D87F86">
        </td>
        <td>
            <input type="number" step="0.01" min="0" name="variants[${idx}][price_modifier]" class="form-input" value="${priceMod}" placeholder="0.00">
        </td>
        <td>
            <input type="number" min="0" name="variants[${idx}][stock_quantity]" class="form-input" value="${stock}" placeholder="10">
        </td>
        <td style="text-align: center;">
            <input type="hidden" name="variants[${idx}][is_active]" value="0">
            <input type="checkbox" name="variants[${idx}][is_active]" value="1" ${isActive ? 'checked' : ''}>
        </td>
        <td style="text-align: center;">
            <button type="button" class="btn-remove-variant-row" onclick="removeVariantRow(this)" title="Eliminar fila">✕</button>
        </td>
    `;

    tbody.appendChild(row);
    if (notice) notice.style.display = 'none';
}

function removeVariantRow(btn) {
    const row = btn.closest('tr');
    const tbody = document.getElementById('variantsTableBody');
    const notice = document.getElementById('noVariantsNotice');
    row.remove();
    if (tbody.children.length === 0 && notice) {
        notice.style.display = 'block';
    }
}

function addPresetVariant(type, name, val) {
    addVariantRow(type, name, val, '0.00', '10', true);
}

document.addEventListener('DOMContentLoaded', function() {
    // Attach buttons for variant addition
    document.getElementById('btnAddSize')?.addEventListener('click', () => addVariantRow('talla', 'Talla ', '', '0.00', '10', true));
    document.getElementById('btnAddColor')?.addEventListener('click', () => addVariantRow('color', 'Color ', '', '0.00', '10', true));
    document.getElementById('btnAddCustom')?.addEventListener('click', () => addVariantRow('personalizado', '', '', '0.00', '10', true));

    // Dropzone & Sortable functionality
    const dropzoneBox = document.getElementById('dropzoneBox');
    const imagesInput = document.getElementById('images_input');
    const sortableGrid = document.getElementById('sortableGrid');
    const gallerySection = document.getElementById('gallerySection');
    const imageOrderInput = document.getElementById('image_order');
    const productForm = document.getElementById('productForm');

    let uploadedFilesRegistry = [];
    let fileIndexCounter = 0;

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
            handleNewFiles(dt.files);
        }
    });

    imagesInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files.length > 0) {
            handleNewFiles(e.target.files);
        }
    });

    function handleNewFiles(files) {
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const uniqueId = 'new_' + fileIndexCounter++;
            const previewUrl = URL.createObjectURL(file);
            uploadedFilesRegistry.push({
                id: uniqueId,
                file: file,
                previewUrl: previewUrl
            });
            appendImageCard(uniqueId, previewUrl, file.name);
        });

        updateGalleryVisibility();
        updateOrderAndBadges();
        syncDataTransferInput();
    }

    function appendImageCard(id, url, title) {
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
            removeCard(id, card);
        });

        sortableGrid.appendChild(card);
    }

    function removeCard(id, cardElement) {
        uploadedFilesRegistry = uploadedFilesRegistry.filter(item => item.id !== id);
        cardElement.remove();
        updateGalleryVisibility();
        updateOrderAndBadges();
        syncDataTransferInput();
    }

    function updateGalleryVisibility() {
        const count = sortableGrid.children.length;
        gallerySection.style.display = count > 0 ? 'block' : 'none';
    }

    function updateOrderAndBadges() {
        const cards = Array.from(sortableGrid.children);
        const orderIds = [];

        cards.forEach((card, index) => {
            const id = card.getAttribute('data-id');
            orderIds.push(id);

            const orderBadge = card.querySelector('.card-badge-order');
            if (orderBadge) orderBadge.textContent = `#${index + 1}`;

            const coverBadge = card.querySelector('.card-badge-cover');
            if (coverBadge) {
                coverBadge.style.display = index === 0 ? 'inline-block' : 'none';
            }
        });

        imageOrderInput.value = JSON.stringify(orderIds);
    }

    function syncDataTransferInput() {
        const dt = new DataTransfer();
        const cards = Array.from(sortableGrid.children);

        cards.forEach(card => {
            const id = card.getAttribute('data-id');
            const found = uploadedFilesRegistry.find(item => item.id === id);
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
            updateOrderAndBadges();
            syncDataTransferInput();
        }
    });

    productForm.addEventListener('submit', function() {
        updateOrderAndBadges();
        syncDataTransferInput();
    });
});
</script>
@endpush
