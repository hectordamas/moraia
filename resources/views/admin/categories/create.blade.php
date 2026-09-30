@extends('layouts.admin')

@section('page_title', 'Nueva Categoría')

@section('admin_content')
<div class="admin-card" style="max-width: 650px; margin: 0 auto;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Crear Categoría</h2>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">&larr; Volver</a>
    </div>

    @if($errors->any())
        <div style="background-color: var(--color-error-bg); border-left: 4px solid var(--color-error); padding: 12px; border-radius: var(--radius-xs); margin-bottom: 20px; color: var(--color-error); font-size: 0.85rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Nombre de la Categoría *</label>
            <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Descripción</label>
            <textarea id="description" name="description" class="form-textarea" rows="3" placeholder="Resumen editorial de la categoría...">{{ old('description') }}</textarea>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
            <div class="form-group">
                <label class="form-label" for="sort_order">Orden de Visualización</label>
                <input type="number" id="sort_order" name="sort_order" class="form-input" value="{{ old('sort_order', 0) }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="image">Imagen de Portada</label>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
            </div>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span><strong>Categoría Activa</strong> (Visible en navegación)</span>
            </label>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', true) ? 'checked' : '' }}>
                <span><strong>Mostrar en Portada</strong> (Grid de categorías del Home)</span>
            </label>
        </div>

        <div style="margin-top: var(--space-6);">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
                Guardar Categoría
            </button>
        </div>
    </form>
</div>
@endsection
