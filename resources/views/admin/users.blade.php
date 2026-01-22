@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #7c3aed;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total User</h6>
                <h2 class="mb-0">{{ $users->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #10b981;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Admin</h6>
                <h2 class="mb-0">{{ $users->where('role', 'admin')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #3b82f6;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Anggota</h6>
                <h2 class="mb-0">{{ $users->where('role', 'anggota')->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="mb-4">Daftar User</h5>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #7c3aed, #a855f7); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: 600; overflow: hidden;">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge" style="background: linear-gradient(135deg, #7c3aed, #a855f7); color: white; padding: 4px 12px; border-radius: 20px;">
                                    Admin
                                </span>
                            @else
                                <span class="badge" style="background: linear-gradient(135deg, #059669, #10b981); color: white; padding: 4px 12px; border-radius: 20px;">
                                    Anggota
                                </span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            @if($user->id !== Auth::id())
                            <form action="/admin/users/{{ $user->id }}/toggle-role" method="POST" style="display: inline;">
                                @csrf
                                @if($user->role === 'admin')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Yakin ingin mengubah {{ $user->name }} menjadi Anggota?')">
                                        ↓ Jadikan Anggota
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-primary" onclick="return confirm('Yakin ingin mengubah {{ $user->name }} menjadi Admin?')">
                                        ↑ Jadikan Admin
                                    </button>
                                @endif
                            </form>
                            @else
                                <span class="text-muted small">Anda sendiri</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
