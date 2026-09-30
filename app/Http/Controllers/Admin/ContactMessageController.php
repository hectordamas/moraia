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

        $messages = $query->paginate(15)->withQueryString();

        return view('admin.messages.index', compact('messages', 'status'));
    }

    public function show(ContactMessage $message): View
    {
        if ($message->status === 'Pendiente') {
            $message->update(['status' => 'Leído']);
        }
        $whatsAppUrl = $message->generateWhatsAppReplyUrl();

        return view('admin.messages.show', compact('message', 'whatsAppUrl'));
    }

    public function updateStatus(Request $request, ContactMessage $message): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pendiente,Leído,Respondido',
        ]);

        $message->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Estado del mensaje actualizado.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Mensaje eliminado.');
    }
}
