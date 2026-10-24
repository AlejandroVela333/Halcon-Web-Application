@extends('layouts.app')

@section('title', 'Track an order - Halcon')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="jumbotron bg-white shadow-sm border">
            <h1 class="display-4">Track your Halcon order</h1>
            <p class="lead">Enter the invoice number to view the current order status.</p>
            <form action="{{ route('orders.track') }}" method="GET" class="mt-4">
                <div class="form-row align-items-end">
                    <div class="col-md-9">
                        <label for="invoice_number">Invoice number</label>
                        <input id="invoice_number" name="invoice_number" type="text"
                               value="{{ old('invoice_number') }}" class="form-control" maxlength="30" required>
                    </div>
                    <div class="col-md-3 mt-3 mt-md-0">
                        <button class="btn btn-primary btn-block" type="submit">Search order</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
