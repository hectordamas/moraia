<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;

class OrderSuccessController extends Controller
{
    public function show(string $order_code): View
    {
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();
        $whatsAppUrl = $order->generateWhatsAppUrl();

        return view('pages.order-success', compact('order', 'whatsAppUrl'));
    }

    public function downloadPdf(string $order_code)
    {
        $order = Order::with(['items.product', 'items.variant'])->where('order_code', $order_code)->firstOrFail();

        $pdf = Pdf::loadView('pdf.order-receipt', compact('order'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        return $pdf->download("Comprobante-MORAIA-{$order->order_code}.pdf");
    }
}
