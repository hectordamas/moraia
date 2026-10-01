@extends('layouts.admin')

@section('page_title', 'Bandeja de Mensajes')

@section('admin_content')
<div class="chat-inbox-container">
    <!-- Left Sidebar: Conversations List -->
    <aside class="chat-inbox-sidebar">
        <div class="chat-sidebar-header">
            <!-- Search Form -->
            <form action="{{ route('admin.messages.index') }}" method="GET" class="chat-search-form">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <svg class="chat-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" name="q" class="chat-search-input" placeholder="Buscar por cliente, asunto o texto..." value="{{ $search ?? '' }}">
            </form>

            <!-- Status Filter Pills -->
            <div class="chat-filter-pills">
                <a href="{{ route('admin.messages.index', array_filter(['q' => $search])) }}" class="chat-filter-pill {{ empty($status) ? 'active' : '' }}">
                    Todos ({{ $messages->count() }})
                </a>
                <a href="{{ route('admin.messages.index', array_filter(['status' => 'Pendiente', 'q' => $search])) }}" class="chat-filter-pill {{ $status === 'Pendiente' ? 'active' : '' }}">
                    Pendientes @if($unreadCount > 0) ({{ $unreadCount }}) @endif
                </a>
                <a href="{{ route('admin.messages.index', array_filter(['status' => 'Leído', 'q' => $search])) }}" class="chat-filter-pill {{ $status === 'Leído' ? 'active' : '' }}">
                    Leídos ({{ $readCount }})
                </a>
                <a href="{{ route('admin.messages.index', array_filter(['status' => 'Respondido', 'q' => $search])) }}" class="chat-filter-pill {{ $status === 'Respondido' ? 'active' : '' }}">
                    Respondidos ({{ $repliedCount }})
                </a>
            </div>
        </div>

        <!-- Scrollable Conversations List -->
        <div class="chat-conversations-list">
            @forelse($messages as $msg)
                @php
                    $isActive = $selectedMessage && $selectedMessage->id === $msg->id;
                    $initials = collect(explode(' ', $msg->name))->map(fn($part) => mb_substr($part, 0, 1))->take(2)->join('');
                @endphp
                <a href="{{ route('admin.messages.index', array_filter(['selected' => $msg->id, 'status' => $status, 'q' => $search])) }}" class="chat-conversation-item {{ $isActive ? 'active' : '' }}">
                    <div class="chat-avatar">
                        {{ strtoupper($initials ?: 'C') }}
                    </div>
                    <div class="chat-item-content">
                        <div class="chat-item-row-top">
                            <span class="chat-item-name">{{ $msg->name }}</span>
                            <span class="chat-item-time">{{ $msg->created_at->format('d/m H:i') }}</span>
                        </div>
                        <div class="chat-item-subject">
                            {{ $msg->subject }}
                        </div>
                        <div class="chat-item-snippet">
                            {{ Str::limit($msg->message, 55) }}
                        </div>
                    </div>
                    @if($msg->status === 'Pendiente')
                        <span class="chat-unread-badge" title="Mensaje no leído"></span>
                    @endif
                </a>
            @empty
                <div style="padding: 2.5rem 1rem; text-align: center; color: var(--color-text-muted); font-size: var(--text-xs);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="margin: 0 auto 0.5rem auto; color: var(--color-border);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    No se encontraron mensajes.
                </div>
            @endforelse
        </div>
    </aside>

    <!-- Right Pane: Active Chat Room -->
    <main class="chat-room-main">
        @if($selectedMessage)
            @php
                $initialsMain = collect(explode(' ', $selectedMessage->name))->map(fn($part) => mb_substr($part, 0, 1))->take(2)->join('');
            @endphp
            <!-- Chat Room Header -->
            <div class="chat-room-header">
                <div class="chat-room-user">
                    <div class="chat-avatar" style="width: 48px; height: 48px; font-size: 1rem;">
                        {{ strtoupper($initialsMain ?: 'C') }}
                    </div>
                    <div>
                        <div class="chat-room-username">{{ $selectedMessage->name }}</div>
                        <div class="chat-room-meta">
                            <span>✉️ <a href="mailto:{{ $selectedMessage->email }}">{{ $selectedMessage->email }}</a></span>
                            @if($selectedMessage->phone)
                                <span>📱 <a href="tel:{{ $selectedMessage->phone }}">{{ $selectedMessage->phone }}</a></span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Chat Room Top Actions -->
                <div class="chat-room-actions">
                    <!-- Status Form -->
                    <form action="{{ route('admin.messages.updateStatus', $selectedMessage->id) }}" method="POST" style="display: inline-flex; align-items: center; gap: 6px;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="filter_status" value="{{ $status }}">
                        <select name="status" class="form-select" style="font-size: var(--text-xs); padding: 0.35rem 0.65rem; height: auto;" onchange="this.form.submit()">
                            <option value="Pendiente" {{ $selectedMessage->status === 'Pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                            <option value="Leído" {{ $selectedMessage->status === 'Leído' ? 'selected' : '' }}>👀 Leído</option>
                            <option value="Respondido" {{ $selectedMessage->status === 'Respondido' ? 'selected' : '' }}>✅ Respondido</option>
                        </select>
                    </form>

                    <!-- Direct WhatsApp Reply -->
                    @if($selectedMessage->phone)
                        <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener" class="btn-whatsapp-action" style="padding: 0.45rem 0.85rem; font-size: var(--text-xs);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span>WhatsApp</span>
                        </a>
                    @endif

                    <!-- Delete Button -->
                    <form action="{{ route('admin.messages.destroy', $selectedMessage->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon-table" title="Eliminar Mensaje" style="color: var(--color-error); padding: 0.45rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Chat Messages Conversation Flow -->
            <div class="chat-room-messages">
                <!-- Date Separator -->
                <div class="chat-date-separator">
                    <span class="chat-date-bubble">
                        {{ $selectedMessage->created_at->translatedFormat('l, d \d\e F \d\e Y') }}
                    </span>
                </div>

                <!-- Customer Inbound Message Bubble -->
                <div class="chat-bubble-inbound">
                    <div class="chat-bubble-subject">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.502 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <span>Asunto: {{ $selectedMessage->subject }}</span>
                    </div>

                    <div class="chat-bubble-text">
                        {!! nl2br(e($selectedMessage->message)) !!}
                    </div>

                    <div class="chat-bubble-footer">
                        <span>📥 Recibido desde formulario web</span>
                        <span>{{ $selectedMessage->created_at->format('H:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Chat Bottom Composer / Reply Assistant -->
            <div class="chat-room-composer">
                <div class="chat-quick-reply-box">
                    <div style="font-weight: 700; color: var(--color-primary-dark); font-size: 0.72rem; text-transform: uppercase; margin-bottom: 2px;">
                        Respuesta Rápida por WhatsApp
                    </div>
                    <div style="font-style: italic; color: var(--color-text-muted);">
                        "Hola {{ $selectedMessage->name }}, gracias por escribirnos a MORAIA. Con respecto a tu consulta: '{{ $selectedMessage->subject }}'..."
                    </div>
                </div>

                <div class="chat-composer-actions">
                    <div class="flex items-center gap-2">
                        @if($selectedMessage->status !== 'Respondido')
                            <form action="{{ route('admin.messages.updateStatus', $selectedMessage->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Respondido">
                                <button type="submit" class="btn btn-outline btn-sm" style="font-size: var(--text-xs); padding: 0.45rem 0.85rem;">
                                    ✓ Marcar como Respondido
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($selectedMessage->phone)
                        <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener" class="btn btn-whatsapp-action" style="padding: 0.5rem 1.25rem; font-size: var(--text-xs); font-weight: 700;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span>Abrir Chat en WhatsApp &rarr;</span>
                        </a>
                    @else
                        <a href="mailto:{{ $selectedMessage->email }}" class="btn btn-primary btn-sm" style="font-size: var(--text-xs); padding: 0.5rem 1rem;">
                            ✉️ Responder por Correo
                        </a>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty Chat State -->
            <div class="chat-empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" style="color: var(--color-primary-light);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
                <h3 style="font-size: var(--text-base); font-weight: 700; color: var(--color-text);">Ningún mensaje seleccionado</h3>
                <p style="font-size: var(--text-xs); max-width: 320px;">
                    Selecciona una conversación de la lista lateral para leer los detalles y responder directamente.
                </p>
            </div>
        @endif
    </main>
</div>
@endsection
