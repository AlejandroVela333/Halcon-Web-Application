<?php

namespace App\Http\Controllers;

use App\Order;

class ArchivedOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::onlyTrashed()
            ->with('customer')
            ->orderByDesc('deleted_at')
            ->get();

        return view('archived-orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::onlyTrashed()
            ->with(['customer', 'creator.role', 'details.material', 'evidencePhotos.uploader'])
            ->findOrFail($id);

        return view('archived-orders.show', compact('order'));
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('archived-orders.index')->with('success', 'Order restored successfully.');
    }
}
