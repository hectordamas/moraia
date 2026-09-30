@extends('layouts.admin')

@section('page_title', 'Configuración de la Tienda')

@section('admin_content')
<div class="admin-card" style="max-width: 800px;">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Ajustes Generales de Moraia</h2>
            <p style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: 2px;">
                Actualiza los datos de contacto, enlaces de redes sociales, tarifas y textos de la tienda.
            </p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <!-- 1. Brand & Contact -->
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text); margin-bottom: var(--space-4); padding-bottom: var(--space-2); border-bottom: 1px solid var(--color-border-light);">
            Canales de Contacto & Redes
        </h3>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
            <div class="form-group">
                <label class="form-label" for="contact_whatsapp">WhatsApp Visible</label>
                <input type="text" id="contact_whatsapp" name="contact_whatsapp" class="form-input" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '+584120206548') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_whatsapp_clean">WhatsApp Formato Internacional (Sólo números)</label>
                <input type="text" id="contact_whatsapp_clean" name="contact_whatsapp_clean" class="form-input" value="{{ old('contact_whatsapp_clean', $settings['contact_whatsapp_clean'] ?? '584120206548') }}">
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
            <div class="form-group">
                <label class="form-label" for="contact_email">Correo Electrónico</label>
                <input type="email" id="contact_email" name="contact_email" class="form-input" value="{{ old('contact_email', $settings['contact_email'] ?? 'By.moraia@gmail.com') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_instagram">Usuario de Instagram</label>
                <input type="text" id="contact_instagram" name="contact_instagram" class="form-input" value="{{ old('contact_instagram', $settings['contact_instagram'] ?? '@by.moraia') }}">
            </div>
        </div>

        <!-- 2. Shipping & Delivery -->
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text); margin-top: var(--space-6); margin-bottom: var(--space-4); padding-bottom: var(--space-2); border-bottom: 1px solid var(--color-border-light);">
            Tarifas de Entrega
        </h3>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
            <div class="form-group">
                <label class="form-label" for="shipping_caracas_price">Costo Delivery en Caracas ($ USD)</label>
                <input type="number" step="0.01" min="0" id="shipping_caracas_price" name="shipping_caracas_price" class="form-input" value="{{ old('shipping_caracas_price', $settings['shipping_caracas_price'] ?? '3.00') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="shipping_caracas_time">Tiempo de Entrega Caracas</label>
                <input type="text" id="shipping_caracas_time" name="shipping_caracas_time" class="form-input" value="{{ old('shipping_caracas_time', $settings['shipping_caracas_time'] ?? 'Entrega el mismo día o en 24h') }}">
            </div>
        </div>

        <!-- 3. Announcement & Texts -->
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text); margin-top: var(--space-6); margin-bottom: var(--space-4); padding-bottom: var(--space-2); border-bottom: 1px solid var(--color-border-light);">
            Mensajes & Barra Superior
        </h3>

        <div class="form-group">
            <label class="form-label" for="announcement_bar_text">Texto de la Barra Superior</label>
            <input type="text" id="announcement_bar_text" name="announcement_bar_text" class="form-input" value="{{ old('announcement_bar_text', $settings['announcement_bar_text'] ?? '🌸 Envíos a toda Venezuela | Delivery propio en Caracas | Atención por WhatsApp') }}">
        </div>

        <div class="form-group">
            <label class="form-label" for="about_manifesto">Manifiesto de Marca (Portada & Nosotros)</label>
            <textarea id="about_manifesto" name="about_manifesto" class="form-textarea" rows="3">{{ old('about_manifesto', $settings['about_manifesto'] ?? 'Moraia existe para reunir en una sola caja todo lo que una mujer necesita para consentirse: lo que se pone, lo que la embellece, lo que la hace sentir deseada y lo que la hace sonreír al abrirla. Regala experiencia.') }}</textarea>
        </div>

        <div style="margin-top: var(--space-8);">
            <button type="submit" class="btn btn-primary btn-lg">
                Guardar Configuraciones
            </button>
        </div>
    </form>
</div>
@endsection
