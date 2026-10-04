@extends('layouts.admin')

@section('page_title', 'Editar Empaque')

@section('admin_content')
<div class="admin-card" style="max-width: 680px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Editar Empaque: {{ $packaging->name }}</h2>
            <p style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: 2px;">
                Modifica los detalles, capacidad, precio o imagen de este empaque.
            </p>
        </div>
        <a href="{{ route('admin.packagings.index') }}" class="btn btn-outline btn-sm">&larr; Volver</a>
    </div>

    @if($errors->any())
        <div style="background-color: var(--color-error-bg); border-left: 4px solid var(--color-error); padding: 12px; border-radius: var(--radius-xs); margin-bottom: 20px; color: var(--color-error); font-size: 0.85rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.packagings.update', $packaging->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label" for="name">Nombre del Empaque *</label>
            <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $packaging->name) }}" required>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
            <div class="form-group">
                <label class="form-label" for="capacity">Capacidad / Unidades</label>
                <input type="text" id="capacity" name="capacity" class="form-input" placeholder="Ej: 1 a 2 unidades, 3 a 5 prendas, etc." value="{{ old('capacity', $packaging->capacity) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="price">Costo Adicional (US$)</label>
                <input type="number" step="0.01" min="0" id="price" name="price" class="form-input" value="{{ old('price', number_format($packaging->price, 2, '.', '')) }}">
                <small style="display: block; font-size: 0.72rem; color: var(--color-text-muted); margin-top: 4px;">
                    Coloca 0.00 para que sea incluido sin costo.
                </small>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Descripción / Detalles de la Presentación</label>
            <textarea id="description" name="description" class="form-textarea" rows="3" placeholder="Describe los materiales, lazo, papel de seda, aroma o acabado...">{{ old('description', $packaging->description) }}</textarea>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
            <div class="form-group">
                <label class="form-label" for="sort_order">Orden de Visualización</label>
                <input type="number" id="sort_order" name="sort_order" class="form-input" value="{{ old('sort_order', $packaging->sort_order) }}">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="image">Cambiar Foto Referencial</label>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
            </div>
        </div>

        @if($packaging->image_path)
            <div style="display: flex; align-items: center; gap: 14px; background: var(--color-surface-soft); padding: 12px; border-radius: var(--radius-xs); border: 1px solid var(--color-border); margin-bottom: var(--space-4);">
                <img src="{{ asset($packaging->image_path) }}" alt="{{ $packaging->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-xs); border: 1px solid var(--color-border);">
                <div>
                    <div style="font-size: var(--text-xs); font-weight: 600; color: var(--color-text);">Foto Actual</div>
                    <label class="form-check" style="margin-top: 4px; font-size: var(--text-xs); cursor: pointer; color: var(--color-error);">
                        <input type="checkbox" name="remove_image" value="1">
                        <span>Eliminar imagen actual</span>
                    </label>
                </div>
            </div>
        @endif

        <div style="background-color: var(--color-surface-soft); border: 1px solid var(--color-border); border-radius: var(--radius-xs); padding: 14px; margin-bottom: var(--space-6);">
            <div class="form-group" style="margin-bottom: 10px;">
                <label class="form-check" style="cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $packaging->is_active) ? 'checked' : '' }}>
                    <span><strong>Empaque Activo</strong> (Disponible para selección en checkout)</span>
                </label>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-check" style="cursor: pointer;">
                    <input type="checkbox" name="is_default" value="1" {{ old('is_default', $packaging->is_default) ? 'checked' : '' }}>
                    <span><strong>Marcar como Predeterminado</strong> (Seleccionado por defecto al entrar al checkout)</span>
                </label>
            </div>
        </div>

        <div style="margin-top: var(--space-6);">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
                Actualizar Empaque
            </button>
        </div>
    </form>
</div>
@endsection
