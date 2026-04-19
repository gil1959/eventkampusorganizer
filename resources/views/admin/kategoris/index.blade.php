@extends('layouts.admin')
@section('title', 'Manajemen Kategori')
@section('page_title', 'Manajemen Kategori')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Kategori</h1>
        <p class="page-subtitle">Kelola kategori event yang tersedia</p>
    </div>
    <button onclick="document.getElementById('addModal').style.display='flex'" class="btn btn-orange">
        <i class="ri-add-line"></i> Tambah Kategori
    </button>
</div>

<div class="card" data-aos="fade-up">
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr><th>No</th><th>Nama Kategori</th><th>Deskripsi</th><th>Total Event</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $i => $k)
                    <tr>
                        <td style="color:var(--gray-400);">{{ $kategoris->firstItem() + $i }}</td>
                        <td style="font-weight:700;color:var(--navy);">
                            <i class="ri-{{ $k->icon ?? 'bookmark-line' }}" style="margin-right:0.4rem;color:var(--orange);"></i>
                            {{ $k->nama_kategori }}
                        </td>
                        <td style="font-size:0.82rem;color:var(--gray-500);">{{ $k->deskripsi ?? '-' }}</td>
                        <td><span class="badge badge-navy">{{ $k->events_count }} event</span></td>
                        <td><span class="badge {{ $k->is_active ? 'badge-success' : 'badge-danger' }}">{{ $k->is_active ? 'Aktif' : 'Non-Aktif' }}</span></td>
                        <td>
                            <div style="display:flex;gap:0.4rem;">
                                <button onclick="openEdit({{ $k->id_kategori }}, '{{ addslashes($k->nama_kategori) }}', '{{ addslashes($k->deskripsi) }}', '{{ $k->icon }}')" class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.kategoris.destroy', $k->id_kategori) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus" {{ $k->events_count > 0 ? 'disabled title=Tidak dapat dihapus karena masih ada event' : '' }}>
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state" style="padding:2.5rem;"><i class="ri-price-tag-3-line"></i><h3>Belum ada kategori</h3></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kategoris->hasPages())<div style="padding:1rem 1.25rem;border-top:1px solid var(--gray-100);">{{ $kategoris->links() }}</div>@endif
    </div>
</div>

{{-- Add Modal --}}
<div class="modal-backdrop" id="addModal" style="display:none;">
    <div class="modal">
        <div class="modal-header"><h3>Tambah Kategori</h3><button onclick="document.getElementById('addModal').style.display='none'" style="background:none;border:none;color:white;cursor:pointer;font-size:1.2rem;"><i class="ri-close-line"></i></button></div>
        <form method="POST" action="{{ route('admin.kategoris.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Nama Kategori <span class="required">*</span></label><input type="text" name="nama_kategori" class="form-control" placeholder="Nama kategori" required></div>
                <div class="form-group"><label class="form-label">Deskripsi</label><input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi singkat"></div>
                <div class="form-group mb-0"><label class="form-label">Icon (Remix Icon name)</label><input type="text" name="icon" class="form-control" placeholder="contoh: presentation-line"></div>
            </div>
            <div class="modal-footer"><button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn btn-outline">Batal</button><button type="submit" class="btn btn-orange"><i class="ri-save-line"></i> Simpan</button></div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-backdrop" id="editModal" style="display:none;">
    <div class="modal">
        <div class="modal-header"><h3>Edit Kategori</h3><button onclick="document.getElementById('editModal').style.display='none'" style="background:none;border:none;color:white;cursor:pointer;font-size:1.2rem;"><i class="ri-close-line"></i></button></div>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Nama Kategori <span class="required">*</span></label><input type="text" name="nama_kategori" id="editNama" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Deskripsi</label><input type="text" name="deskripsi" id="editDeskripsi" class="form-control"></div>
                <div class="form-group mb-0"><label class="form-label">Icon</label><input type="text" name="icon" id="editIcon" class="form-control"></div>
            </div>
            <div class="modal-footer"><button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn btn-outline">Batal</button><button type="submit" class="btn btn-orange"><i class="ri-save-line"></i> Simpan</button></div>
        </form>
    </div>
</div>

<script>
    function openEdit(id, nama, deskripsi, icon) {
        document.getElementById('editForm').action = '/admin/kategoris/' + id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editDeskripsi').value = deskripsi;
        document.getElementById('editIcon').value = icon;
        document.getElementById('editModal').style.display = 'flex';
    }
</script>
@endsection
