@extends('layouts.app')
@section('title', 'Archived order '.$order->invoice_number.' - Halcon')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1>Archived order {{ $order->invoice_number }}</h1><a href="{{ route('archived-orders.index') }}" class="btn btn-outline-secondary">Back</a></div>
<div class="card shadow-sm"><div class="card-body"><dl class="row"><dt class="col-sm-3">Customer</dt><dd class="col-sm-9">{{ $order->customer->name }}</dd><dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ $order->status }}</dd><dt class="col-sm-3">Order date</dt><dd class="col-sm-9">{{ $order->order_date->format('Y-m-d H:i') }}</dd><dt class="col-sm-3">Archived at</dt><dd class="col-sm-9">{{ $order->deleted_at->format('Y-m-d H:i') }}</dd><dt class="col-sm-3">Address</dt><dd class="col-sm-9">{{ $order->delivery_address }}</dd></dl><form action="{{ route('archived-orders.restore', $order->id) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-success">Restore order</button></form></div></div>
@endsection
