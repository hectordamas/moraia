@extends('layouts.admin')

@section('page_title', 'Mensaje de ' . $message->name)

@section('admin_content')
<div class="grid" style="grid-template-columns: 2fr 1fr; gap: var(--space-8); align-items: start;">
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-card-title">{{ $message->subject }}</h2>
                <span style="font-size: var(--text-xs); color: var(--color-text-muted);">
                    Recibido el {{ $message->created_at->format('d/m/Y \a \l\a\s H:i') }}
                </span>
            </div>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline btn-sm">&larr; Volver</a>
        </div>

        <div style="background-color: var(--color-surface-soft); padding: var(--space-6); border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: var(--space-6);">
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4); font-size: var(--text-sm); margin-bottom: var(--space-4);">
                <div>
                    <strong>De:</strong> {{ $message->name }}
                </div>
                <div>
                    <strong>Email:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                </div>
                @if($message->phone)
                    <div>
                        <strong>Teléfono / WhatsApp:</strong> {{ $message->phone }}
                    </div>
                @endif
            </div>

            <div style="padding-top: var(--space-4); border-top: 1px solid var(--color-border-light); line-height: 1.8; color: var(--color-text-light); white-space: pre-line;">
                {{ $message->message }}
            </div>
        </div>

        <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline btn-sm" style="color: var(--color-error); border-color: var(--color-error);">
                Eliminar Mensaje
            </button>
        </form>
    </div>

    <!-- Actions & WhatsApp -->
    <div>
        @if($message->phone)
            <div class="admin-card" style="background-color: #FAF5F2; border-color: var(--color-primary-light);">
                <h3 style="font-family: var(--font-display); font-size: 1.25rem; margin-bottom: 8px;">
                    Responder por WhatsApp
                </h3>
                <p style="font-size: var(--text-xs); color: var(--color-text-muted); margin-bottom: var(--space-4);">
                    Inicia una conversación directa respondiendo a su consulta.
                </p>

                <a href="{{ $whatsAppUrl }}" 
                   target="_blank" 
                   rel="noopener" 
                   class="btn btn-block" 
                   style="background-color: #25D366; color: #FFFFFF; border: 1px solid #25D366; font-size: 0.95rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    Contactar por WhatsApp
                </a>
            </div>
        @endif

        <div class="admin-card">
            <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; margin-bottom: var(--space-4);">
                Estado del Mensaje
            </h3>

            <form action="{{ route('admin.messages.updateStatus', $message->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <select name="status" class="form-select">
                        <option value="Pendiente" {{ $message->status === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Leído" {{ $message->status === 'Leído' ? 'selected' : '' }}>Leído</option>
                        <option value="Respondido" {{ $message->status === 'Respondido' ? 'selected' : '' }}>Respondido</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary btn-block btn-sm">
                    Guardar Estado
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
