@extends('layouts.app')
@section('title', 'Create order - Halcon')
@section('content')
<h1>Create order</h1><div class="card shadow-sm"><div class="card-body"><form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data">@csrf @include('orders._form')</form></div></div>
@endsection
