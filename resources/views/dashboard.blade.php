@extends('layouts.app')

@section('title', 'Dashboard - Halcon')

@section('content')
<h1 class="mb-4">Dashboard</h1>
<div class="row">
    <div class="col-md-4 mb-3"><div class="card shadow-sm h-100"><div class="card-body"><h2 class="h5">Users</h2><p>{{ $activeUsers }} active users</p><a href="{{ route('users.index') }}" class="btn btn-primary">Manage users</a></div></div></div>
    <div class="col-md-4 mb-3"><div class="card shadow-sm h-100"><div class="card-body"><h2 class="h5">Orders</h2><p>{{ $openOrders }} open orders</p><a href="{{ route('orders.index') }}" class="btn btn-primary">Manage orders</a></div></div></div>
    <div class="col-md-4 mb-3"><div class="card shadow-sm h-100"><div class="card-body"><h2 class="h5">Archived Orders</h2><p>{{ $archivedOrders }} archived orders</p><a href="{{ route('archived-orders.index') }}" class="btn btn-primary">View archive</a></div></div></div>
</div>
@endsection
