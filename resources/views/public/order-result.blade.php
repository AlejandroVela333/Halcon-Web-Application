@extends('layouts.app')

@section('title', 'Order status - Halcon')

@section('content')
@php
    $deliveryEvidence = $order->evidencePhotos->firstWhere('type', 'Delivery');
@endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Order status</h1>
    <a href="{{ route('home') }}" class="btn btn-outline-secondary">New search</a>
</div>
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between">
        <strong>Invoice {{ $order->invoice_number }}</strong>
        <span class="badge badge-primary p-2">{{ $order->status }}</span>
    </div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-4">Customer</dt><dd class="col-sm-8">{{ $order->customer->name }}</dd>
            <dt class="col-sm-4">Order date</dt><dd class="col-sm-8">{{ $order->order_date->format('Y-m-d H:i') }}</dd>
            <dt class="col-sm-4">Delivery address</dt><dd class="col-sm-8">{{ $order->delivery_address }}</dd>
            @if($order->status === 'In Process')
                <dt class="col-sm-4">Current process</dt><dd class="col-sm-8">{{ $order->status }}</dd>
                <dt class="col-sm-4">Process date</dt><dd class="col-sm-8">{{ $order->updated_at->format('Y-m-d H:i') }}</dd>
            @endif
            @if($order->status === 'Delivered')
                <dt class="col-sm-4">Delivered / updated</dt>
                <dd class="col-sm-8">{{ optional($deliveryEvidence)->uploaded_at ? $deliveryEvidence->uploaded_at->format('Y-m-d H:i') : $order->updated_at->format('Y-m-d H:i') }}</dd>
            @endif
        </dl>
        @if($order->status === 'Delivered' && $deliveryEvidence)
            <hr>
            <h2 class="h5">Delivery evidence</h2>
            <img src="{{ asset('storage/'.$deliveryEvidence->file_path) }}" class="img-fluid rounded border" alt="Delivery evidence">
        @endif
    </div>
</div>
@endsection
