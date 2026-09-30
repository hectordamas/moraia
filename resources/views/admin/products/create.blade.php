@extends('layouts.admin')

@section('page_title', 'Crear Nuevo Producto')

@section('admin_content')
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Información del Producto</h2>
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: var(--space-8); align-items: start;">
            <!-- Main Details -->
            <div>
                <div class="form-group">
                    <label class="form-label" for="name">Nombre del Producto *</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
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
                        <label class="form-label" for="stock_quantity">Stock Disponible *</label>
                        <input type="number" min="0" id="stock_quantity" name="stock_quantity" class="form-input" value="{{ old('stock_quantity', 10) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="short_description">Descripción Corta</label>
                    <textarea id="short_description" name="short_description" class="form-textarea" rows="2" placeholder="Resumen conciso visible en tarjetas y cabecera de producto...">{{ old('short_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Descripción Completa & Cuidados</label>
                    <textarea id="description" name="description" class="form-textarea" rows="6" placeholder="Detalles de confección, materiales, cómo usar o regalar...">{{ old('description') }}</textarea>
                </div>

                <!-- SEO Section -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-top: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-4);">
                        Optimización SEO
                    </h3>
                    <div class="form-group">
                        <label class="form-label" for="seo_title">Título SEO (Etiqueta Title)</label>
                        <input type="text" id="seo_title" name="seo_title" class="form-input" value="{{ old('seo_title') }}" placeholder="Ej: Pijama Seda Satinada | Moraia">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="seo_description">Meta Descripción SEO</label>
                        <textarea id="seo_description" name="seo_description" class="form-textarea" rows="2" placeholder="Resumen que aparecerá en Google...">{{ old('seo_description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar Controls & Images -->
            <div>
                <!-- Images Upload -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-3);">
                        Fotografías del Producto
                    </h3>
                    <div class="form-group">
                        <label class="form-label" for="images">Seleccionar Imágenes (JPG / PNG / WebP)</label>
                        <input type="file" id="images" name="images[]" class="form-input" multiple accept="image/*">
                        <span style="font-size: var(--text-xs); color: var(--color-text-muted); display: block; margin-top: 4px;">
                            📐 <strong>Formato requerido:</strong> Relación de aspecto 1:1 (cuadrada). Recomendado <strong>500x500 px</strong>.
                        </span>
                        <span style="font-size: var(--text-xs); color: var(--color-text-muted); display: block; margin-top: 2px;">
                            Puedes seleccionar varias imágenes a la vez. La primera será la portada.
                        </span>
                    </div>
                </div>

                <!-- SKU & Badges -->
                <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
                    <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; color: var(--color-text); margin-bottom: var(--space-3);">
                        Identificadores & Distintivos
                    </h3>

                    <div class="form-group">
                        <label class="form-label" for="sku">Código SKU</label>
                        <input type="text" id="sku" name="sku" class="form-input" placeholder="Ej: MOR-PIJ-01" value="{{ old('sku') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="badge">Badge / Etiqueta Visual</label>
                        <input type="text" id="badge" name="badge" class="form-input" placeholder="Ej: Best Seller / Exclusivo / Nuevo" value="{{ old('badge') }}">
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

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Guardar Producto
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
