<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:30',
        ]);

        $order = Order::with(['customer', 'evidencePhotos' => function ($query) {
            $query->orderByDesc('uploaded_at');
        }])->where('invoice_number', $validated['invoice_number'])->first();

        if (!$order) {
            return redirect()->route('home')
                ->withInput()
                ->withErrors(['invoice_number' => 'No order was found for that invoice number.']);
        }

        return view('public.order-result', compact('order'));
    }
}
