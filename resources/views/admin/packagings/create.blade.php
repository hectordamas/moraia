@extends('layouts.admin')

@section('page_title', 'Nuevo Empaque')

@section('admin_content')
<div class="admin-card" style="max-width: 680px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Crear Opción de Empaque</h2>
            <p style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: 2px;">
                Configura un tipo de empaque o presentación (bolsas, cajas de diferentes unidades, etc.)
            </p>
        </div>
        <a href="{{ route('admin.packagings.index') }}" class="btn btn-outline btn-sm">&larr; Volver</a>
    </div>

    @if($errors->any())
        <div style="background-color: var(--color-error-bg); border-left: 4px solid var(--color-error); padding: 12px; border-radius: var(--radius-xs); margin-bottom: 20px; color: var(--color-error); font-size: 0.85rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.packagings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Nombre del Empaque *</label>
            <input type="text" id="name" name="name" class="form-input" placeholder="Ej: Bolsa de Satén & Lazo / Caja Rígida de Lujo (1 a 3 prendas)" value="{{ old('name') }}" required>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
            <div class="form-group">
                <label class="form-label" for="capacity">Capacidad / Unidades</label>
                <input type="text" id="capacity" name="capacity" class="form-input" placeholder="Ej: 1 a 2 unidades, 3 a 5 prendas, etc." value="{{ old('capacity') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="price">Costo Adicional (US$)</label>
                <input type="number" step="0.01" min="0" id="price" name="price" class="form-input" placeholder="0.00 si es gratis" value="{{ old('price', '0.00') }}">
                <small style="display: block; font-size: 0.72rem; color: var(--color-text-muted); margin-top: 4px;">
                    Coloca 0.00 para que sea incluido sin costo.
                </small>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Descripción / Detalles de la Presentación</label>
            <textarea id="description" name="description" class="form-textarea" rows="3" placeholder="Describe los materiales, lazo, papel de seda, aroma o acabado...">{{ old('description') }}</textarea>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
            <div class="form-group">
                <label class="form-label" for="sort_order">Orden de Visualización</label>
                <input type="number" id="sort_order" name="sort_order" class="form-input" value="{{ old('sort_order', 0) }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="image">Foto Referencial del Empaque</label>
                <input type="file" id="image" name="image" class="form-input" accept="image/*">
            </div>
        </div>

        <div style="background-color: var(--color-surface-soft); border: 1px solid var(--color-border); border-radius: var(--radius-xs); padding: 14px; margin-bottom: var(--space-6);">
            <div class="form-group" style="margin-bottom: 10px;">
                <label class="form-check" style="cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span><strong>Empaque Activo</strong> (Disponible para selección en checkout)</span>
                </label>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-check" style="cursor: pointer;">
                    <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                    <span><strong>Marcar como Predeterminado</strong> (Seleccionado por defecto al entrar al checkout)</span>
                </label>
            </div>
        </div>

        <div style="margin-top: var(--space-6);">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
                Guardar Empaque
            </button>
        </div>
    </form>
</div>
@endsection
