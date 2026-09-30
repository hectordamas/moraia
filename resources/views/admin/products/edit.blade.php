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

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: var(--space-8); align-items: start;">
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
                        <label class="form-label" for="stock_quantity">Stock Disponible *</label>
                        <input type="number" min="0" id="stock_quantity" name="stock_quantity" class="form-input" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="short_description">Descripción Corta</label>
                    <textarea id="short_description" name="short_description" class="form-textarea" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Descripción Completa</label>
                    <textarea id="description" name="description" class="form-textarea" rows="6">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Existing Photos Showcase -->
                @if($product->images->isNotEmpty())
                    <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-top: var(--space-6);">
                        <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-3);">
                            Fotografías Actuales
                        </h3>
                        <div class="flex gap-4" style="flex-wrap: wrap;">
                            @foreach($product->images as $img)
                                <div style="position: relative; width: 80px; height: 80px; aspect-ratio: 1 / 1; border-radius: var(--radius-xs); overflow: hidden; border: 1px solid var(--color-border); background-color: var(--color-surface);">
                                    <img src="{{ asset($img->image_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                                    @if($img->is_cover)
                                        <span class="badge badge-rose" style="position: absolute; bottom: 2px; left: 2px; font-size: 0.55rem; padding: 2px 4px;">Portada</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

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

            <!-- Sidebar Controls -->
            <div>
                <!-- Add More Images -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-3);">
                        Agregar Más Fotografías
                    </h3>
                    <div class="form-group" style="margin-bottom: 0;">
                        <input type="file" name="images[]" class="form-input" multiple accept="image/*">
                        <span style="font-size: var(--text-xs); color: var(--color-text-muted); display: block; margin-top: 4px;">
                            📐 <strong>Formato requerido:</strong> Relación de aspecto 1:1 (cuadrada). Recomendado <strong>500x500 px</strong>.
                        </span>
                    </div>
                </div>

                <!-- Identifiers & Badges -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
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

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Actualizar Producto
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
