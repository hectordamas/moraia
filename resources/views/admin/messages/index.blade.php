@extends('layouts.admin')

@section('page_title', 'Bandeja de Mensajes')

@section('admin_content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Mensajes de Contacto</h2>
            <p style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: 2px;">
                Consultas recibidas desde el formulario web con enlace directo para responder por WhatsApp.
            </p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.messages.index') }}" style="display: flex; gap: var(--space-4); margin-bottom: var(--space-6);">
        <select name="status" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
            <option value="">Todos los Mensajes</option>
            <option value="Pendiente" {{ request('status') === 'Pendiente' ? 'selected' : '' }}>Pendientes</option>
            <option value="Leído" {{ request('status') === 'Leído' ? 'selected' : '' }}>Leídos</option>
            <option value="Respondido" {{ request('status') === 'Respondido' ? 'selected' : '' }}>Respondidos</option>
        </select>
        @if(request('status'))
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline btn-sm">Limpiar</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>WhatsApp</th>
                    <th>Asunto</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td><strong>{{ $msg->name }}</strong></td>
                        <td>{{ $msg->email }}</td>
                        <td>
                            @if($msg->phone)
                                <a href="{{ $msg->generateWhatsAppReplyUrl() }}" target="_blank" rel="noopener" class="btn-whatsapp-action">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    {{ $msg->phone }}
                                </a>
                            @else
                                <span style="color: var(--color-text-soft); font-size: var(--text-xs);">Sin teléfono</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($msg->subject, 30) }}</td>
                        <td>
                            @if($msg->status === 'Pendiente')
                                <span class="badge badge-rose">Pendiente</span>
                            @elseif($msg->status === 'Leído')
                                <span class="badge badge-preparing">Leído</span>
                            @else
                                <span class="badge badge-confirmed">Respondido</span>
                            @endif
                        </td>
                        <td style="font-size: var(--text-xs); color: var(--color-text-muted);">
                            {{ $msg->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn-icon-table" title="Ver Mensaje">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-table" title="Eliminar" style="color: var(--color-error);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px;">No hay mensajes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($messages->hasPages())
        <div style="margin-top: var(--space-6); display: flex; justify-content: center;">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection
