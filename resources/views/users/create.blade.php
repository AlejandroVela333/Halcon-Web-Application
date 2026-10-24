@extends('layouts.app')
@section('title', 'Create user - Halcon')
@section('content')
<h1>Create user</h1><div class="card shadow-sm"><div class="card-body"><form method="POST" action="{{ route('users.store') }}">@csrf @include('users._form')</form></div></div>
@endsection
