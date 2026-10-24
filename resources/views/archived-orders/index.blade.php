@extends('layouts.app')
@section('title', 'Archived Orders - Halcon')
@section('content')
<h1>Archived Orders</h1><p class="text-muted">Only logically deleted orders are shown.</p>
<div class="table-responsive bg-white shadow-sm border rounded"><table class="table table-striped mb-0"><thead><tr><th>Invoice</th><th>Customer</th><th>Status</th><th>Archived at</th><th>Actions</th></tr></thead><tbody>
@forelse($orders as $order)<tr><td>{{ $order->invoice_number }}</td><td>{{ $order->customer->name }}</td><td>{{ $order->status }}</td><td>{{ $order->deleted_at->format('Y-m-d H:i') }}</td><td><a href="{{ route('archived-orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary">View</a> <form action="{{ route('archived-orders.restore', $order->id) }}" method="POST" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-success">Restore</button></form></td></tr>@empty<tr><td colspan="5">No archived orders found.</td></tr>@endforelse
</tbody></table></div>
@endsection
