@extends('layouts.app')
@section('title', 'Orders - Halcon')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1>Orders</h1><a href="{{ route('orders.create') }}" class="btn btn-primary">Create order</a></div>
<p class="text-muted">Orders are listed from newest to oldest by order date.</p>
<div class="table-responsive bg-white shadow-sm border rounded"><table class="table table-striped mb-0">
<thead><tr><th>Invoice</th><th>Customer</th><th>Order date</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse($orders as $order)
<tr><td>{{ $order->invoice_number }}</td><td>{{ $order->customer->name }}</td><td>{{ $order->order_date->format('Y-m-d H:i') }}</td><td><span class="badge badge-info">{{ $order->status }}</span></td><td class="text-nowrap"><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">View</a> <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-primary">Edit</a> <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive this order?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Archive</button></form></td></tr>
@empty<tr><td colspan="5">No active orders found.</td></tr>@endforelse
</tbody></table></div>
@endsection
