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

        <div class="grid" style="grid-template-columns: 2fr 1.2fr; gap: var(--space-8); align-items: start;">
            <!-- Main Details -->
            <div>
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
                            <p>Administra las tallas, colores o presentaciones disponibles para este producto.</p>
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
                                @foreach($product->allVariants as $vIndex => $v)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="variants[{{ $vIndex }}][id]" value="{{ $v->id }}">
                                            <select name="variants[{{ $vIndex }}][variant_type]" class="form-select">
                                                <option value="talla" {{ $v->variant_type === 'talla' ? 'selected' : '' }}>Talla</option>
                                                <option value="color" {{ $v->variant_type === 'color' ? 'selected' : '' }}>Color</option>
                                                <option value="presentacion" {{ $v->variant_type === 'presentacion' ? 'selected' : '' }}>Presentación</option>
                                                <option value="personalizado" {{ $v->variant_type === 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="variants[{{ $vIndex }}][name]" class="form-input" value="{{ $v->name }}" placeholder="Ej: Talla S o Rosa Mauve" required>
                                        </td>
                                        <td>
                                            <input type="text" name="variants[{{ $vIndex }}][value]" class="form-input" value="{{ $v->value }}" placeholder="Ej: S o #D87F86">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="variants[{{ $vIndex }}][price_modifier]" class="form-input" value="{{ $v->price_modifier }}" placeholder="0.00">
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="variants[{{ $vIndex }}][stock_quantity]" class="form-input" value="{{ $v->stock_quantity }}" placeholder="10">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="hidden" name="variants[{{ $vIndex }}][is_active]" value="0">
                                            <input type="checkbox" name="variants[{{ $vIndex }}][is_active]" value="1" {{ $v->is_active ? 'checked' : '' }}>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn-remove-variant-row" onclick="removeVariantRow(this)" title="Eliminar fila">✕</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="noVariantsNotice" class="variants-empty-notice" style="{{ $product->allVariants->isNotEmpty() ? 'display: none;' : '' }}">
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
            <div>
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
                        <label class="form-label" for="sku">Código SKU</label>
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
    </form>
</div>
@endsection

@push('scripts')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
let variantRowIndex = {{ $product->allVariants->count() + 10 }};

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

    // Dropzone & Sortable Gallery
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
