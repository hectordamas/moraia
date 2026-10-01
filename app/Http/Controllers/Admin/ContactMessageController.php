<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $query = ContactMessage::orderBy('id', 'desc');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->get();

        $selectedId = $request->input('selected');
        $selectedMessage = null;

        if ($selectedId) {
            $selectedMessage = $messages->firstWhere('id', (int) $selectedId)
                ?? ContactMessage::find((int) $selectedId);
        } else {
            $selectedMessage = $messages->first();
        }

        // Auto mark as Leído if selected message is Pendiente
        if ($selectedMessage && $selectedMessage->status === 'Pendiente') {
            $selectedMessage->update(['status' => 'Leído']);
        }

        $unreadCount = ContactMessage::where('status', 'Pendiente')->count();
        $readCount = ContactMessage::where('status', 'Leído')->count();
        $repliedCount = ContactMessage::where('status', 'Respondido')->count();

        $whatsAppUrl = $selectedMessage ? $selectedMessage->generateWhatsAppReplyUrl() : '';

        return view('admin.messages.index', compact(
            'messages',
            'selectedMessage',
            'status',
            'search',
            'unreadCount',
            'readCount',
            'repliedCount',
            'whatsAppUrl'
        ));
    }

    public function show(ContactMessage $message): RedirectResponse
    {
        return redirect()->route('admin.messages.index', ['selected' => $message->id]);
    }

    public function updateStatus(Request $request, ContactMessage $message): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pendiente,Leído,Respondido',
        ]);

        $message->update(['status' => $validated['status']]);

        return redirect()->route('admin.messages.index', [
            'selected' => $message->id,
            'status' => $request->input('filter_status'),
        ])->with('success', 'Estado del mensaje actualizado.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Mensaje eliminado.');
    }
}
