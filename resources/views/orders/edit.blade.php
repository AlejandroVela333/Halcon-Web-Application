@extends('layouts.app')
@section('title', 'Edit order - Halcon')
@section('content')
<h1>Edit order {{ $order->invoice_number }}</h1><div class="card shadow-sm"><div class="card-body"><form method="POST" action="{{ route('orders.update', $order) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('orders._form')</form></div></div>
@endsection
