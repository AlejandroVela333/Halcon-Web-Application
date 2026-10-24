@extends('layouts.app')
@section('title', 'Users - Halcon')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1>Users</h1><a href="{{ route('users.create') }}" class="btn btn-primary">Create user</a></div>
<div class="table-responsive bg-white shadow-sm border rounded">
<table class="table table-striped mb-0">
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse($users as $user)
        <tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->role->name }}</td><td><span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td><td><a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a></td></tr>
    @empty<tr><td colspan="5">No users found.</td></tr>@endforelse
    </tbody>
</table>
</div>
@endsection
