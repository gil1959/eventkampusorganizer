@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}"><i class="ri-home-4-line"></i></a>
    <span>/</span>
    <span>Manajemen Pengguna</span>
</div>

<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Pengguna</h1>
        <p class="page-subtitle">Kelola akun panitia dan peserta terdaftar</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-orange">
        <i class="ri-user-add-line"></i> Tambah Pengguna
    </a>
</div>

{{-- Filter Bar --}}
<div class="card" style="margin-bottom:1.5rem;" data-aos="fade-down">
    <div class="card-body" style="padding:1.25rem;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label" style="font-size:0.75rem;">Cari Pengguna</label>
                <div class="input-group">
                    <i class="ri-search-line input-icon"></i>
                    <input type="text" name="search" class="form-control" placeholder="Nama, email, atau NPM..." value="{{ request('search') }}">
                </div>
            </div>
            <div style="min-width:150px;">
                <label class="form-label" style="font-size:0.75rem;">Filter Role</label>
                <select name="role" class="form-control">
                    <option value="">Semua Role</option>
                    <option value="panitia" {{ request('role') === 'panitia' ? 'selected' : '' }}>Panitia</option>
                    <option value="peserta" {{ request('role') === 'peserta' ? 'selected' : '' }}>Peserta</option>
                </select>
            </div>
            <div style="min-width:170px;">
                <label class="form-label" style="font-size:0.75rem;">Filter Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="belum_verifikasi" {{ request('status') === 'belum_verifikasi' ? 'selected' : '' }}>Belum Terverifikasi</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="ri-filter-line"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                    <i class="ri-refresh-line"></i> Reset
                </a>
            @endif
        </form>
    </div>
</div>

{{-- User Table --}}
<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">
            Daftar Pengguna
            <span style="font-size:0.8rem;color:var(--gray-400);font-weight:400;margin-left:0.5rem;">({{ $users->total() }} total)</span>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>NPM / Identitas</th>
                        <th>Program Studi</th>
                        <th>Role</th>
                        <th>Verifikasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $u)
                    <tr>
                        <td style="color:var(--gray-400);font-size:0.8rem;">{{ $users->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.6rem;">
                                <div style="width:34px;height:34px;border-radius:50%;background:{{ $u->role === 'panitia' ? 'linear-gradient(135deg,var(--orange),var(--orange-600))' : 'linear-gradient(135deg,var(--navy),var(--navy-light))' }};display:flex;align-items:center;justify-content:center;color:white;font-size:0.85rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($u->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.875rem;">{{ $u->nama }}</div>
                                    <div style="font-size:0.72rem;color:var(--gray-400);">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:0.8rem;font-family:monospace;font-weight:600;">{{ $u->npm_nip ?? '-' }}</td>
                        <td style="font-size:0.8rem;color:var(--gray-500);">{{ $u->jurusan ?? '-' }}</td>
                        <td>
                            @if($u->role === 'panitia')
                                <span class="badge badge-orange"><i class="ri-team-line"></i> Panitia</span>
                            @else
                                <span class="badge badge-navy"><i class="ri-user-line"></i> Peserta</span>
                            @endif
                        </td>
                        <td>
                            @if($u->verifikasi)
                                <span class="badge badge-success"><i class="ri-check-line"></i> Terverifikasi</span>
                            @else
                                <span class="badge badge-warning"><i class="ri-time-line"></i> Menunggu</span>
                            @endif
                        </td>
                        <td>
                            @if($u->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Non-Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:0.4rem;">
                                <a href="{{ route('admin.users.edit', $u->id_user) }}" class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                @if($u->role === 'panitia')
                                <form method="POST" action="{{ route('admin.users.verifikasi', $u->id_user) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-icon {{ $u->verifikasi ? 'btn-danger' : 'btn-success' }}" title="{{ $u->verifikasi ? 'Cabut Verifikasi' : 'Verifikasi' }}">
                                        <i class="ri-{{ $u->verifikasi ? 'close' : 'check' }}-line"></i>
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.users.destroy', $u->id_user) }}"
                                      onsubmit="return confirm('Hapus akun {{ addslashes($u->nama) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state" style="padding:3rem;">
                                <i class="ri-user-search-line"></i>
                                <h3>Tidak ada pengguna ditemukan</h3>
                                <p>Coba ubah filter pencarian</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div style="padding:1rem 1.25rem; border-top:1px solid var(--gray-100);">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
