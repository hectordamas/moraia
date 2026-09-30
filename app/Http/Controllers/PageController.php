<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $manifesto = Setting::get('about_manifesto');

        return view('pages.about', compact('manifesto'));
    }

    public function contact(): View
    {
        $phone = Setting::get('contact_whatsapp', '+584120206548');
        $email = Setting::get('contact_email', 'By.moraia@gmail.com');
        $instagram = Setting::get('contact_instagram', '@by.moraia');

        return view('pages.contact', compact('phone', 'email', 'instagram'));
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create($validated);

        return redirect()->back()->with('success', '¡Gracias por escribirnos! Tu mensaje ha sido recibido con amor. Te responderemos muy pronto.');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function shippingReturns(): View
    {
        return view('pages.shipping-returns');
    }
}
