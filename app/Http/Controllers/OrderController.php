<?php

namespace App\Http\Controllers;

use App\Customer;
use App\EvidencePhoto;
use App\Material;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::with('customer')
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validatedOrder($request);

        $order = DB::transaction(function () use ($request, $validated) {
            $order = Order::create([
                'invoice_number' => $validated['invoice_number'],
                'customer_id' => $validated['customer_id'],
                'created_by' => Auth::id(),
                'order_date' => $validated['order_date'],
                'delivery_address' => $validated['delivery_address'],
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'],
            ]);

            $order->details()->create([
                'material_id' => $validated['material_id'],
                'quantity' => $validated['quantity'],
            ]);

            $this->storeEvidence($request, $order);

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'creator.role', 'details.material', 'evidencePhotos.uploader']);

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('details');

        return view('orders.edit', array_merge($this->formData(), compact('order')));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $this->validatedOrder($request, $order);

        DB::transaction(function () use ($request, $validated, $order) {
            $order->update([
                'invoice_number' => $validated['invoice_number'],
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'delivery_address' => $validated['delivery_address'],
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'],
            ]);

            $order->details()->delete();
            $order->details()->create([
                'material_id' => $validated['material_id'],
                'quantity' => $validated['quantity'],
            ]);

            $this->storeEvidence($request, $order);
        });

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order archived successfully.');
    }

    private function formData()
    {
        return [
            'customers' => Customer::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'statuses' => Order::STATUSES,
        ];
    }

    private function validatedOrder(Request $request, Order $order = null)
    {
        $invoiceRule = Rule::unique('orders', 'invoice_number');
        if ($order) {
            $invoiceRule->ignore($order->id);
        }

        $validator = Validator::make($request->all(), [
            'invoice_number' => ['required', 'string', 'max:30', $invoiceRule],
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'delivery_address' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => ['required', Rule::in(Order::STATUSES)],
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|integer|min:1',
            'evidence_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->hasFile('evidence_photo') &&
                !in_array($request->input('status'), [Order::STATUS_IN_ROUTE, Order::STATUS_DELIVERED], true)) {
                $validator->errors()->add(
                    'evidence_photo',
                    'Photographic evidence is only allowed for In Route or Delivered orders.'
                );
            }
        });

        return $validator->validate();
    }

    private function storeEvidence(Request $request, Order $order)
    {
        if (!$request->hasFile('evidence_photo')) {
            return;
        }

        $path = $request->file('evidence_photo')->store('order-evidence', 'public');
        $type = $order->status === Order::STATUS_DELIVERED
            ? EvidencePhoto::TYPE_DELIVERY
            : EvidencePhoto::TYPE_LOADED;

        $order->evidencePhotos()->create([
            'uploaded_by' => Auth::id(),
            'type' => $type,
            'file_path' => $path,
            'uploaded_at' => now(),
        ]);
    }
}
