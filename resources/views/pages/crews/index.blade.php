@extends('layouts.panel', ['page' => 'crews'])

@section('page_title', 'Kelola Crew')
@section('page_subtitle', '/ Manajemen Kru Film')

@section('content')
<div class="section-header">
  <div>
    <h2>Kelola Crew</h2>
    <p class="text-muted">Manajemen data kru produksi film</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('crew-modal')">
    <i class="fa-solid fa-plus"></i> Tambah Crew
  </button>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari crew..." oninput="filterTable(this,'crew-table')">
  </div>
  <select class="filter-select" onchange="filterByDepartment(this,'crew-table')">
    <option value="">Semua Departemen</option>
    <option>Sutradara</option>
    <option>Sinematografi</option>
    <option>Produksi</option>
    <option>Artistik</option>
    <option>Suara</option>
    <option>Editing</option>
    <option>VFX</option>
  </select>
  <select class="filter-select" onchange="filterByFilm(this,'crew-table')">
    <option value="">Semua Film</option>
    @foreach ($films as $film)
      <option value="{{ $film->title }}">{{ $film->title }}</option>
    @endforeach
  </select>
</div>

<div class="table-wrap">
  <table id="crew-table">
    <thead>
      <tr>
        <th>#</th><th>Nama</th><th>Posisi</th><th>Departemen</th>
        <th>Film</th><th>Kontak</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($crews as $crew)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>
          <div class="avatar-cell">
            <div class="avatar-sm">{{ substr($crew->name, 0, 2) }}</div>
            {{ $crew->name }}
          </div>
        </td>
        <td>{{ $crew->position ?? '-' }}</td>
        <td><span class="badge badge-purple">{{ $crew->department ?? '-' }}</span></td>
        <td>{{ $crew->film?->title ?? '-' }}</td>
        <td>{{ $crew->phone ?? '-' }}</td>
        <td>
          @php
            $sc = match($crew->status) {
              'Aktif' => 'badge-green',
              'Tidak Aktif' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $crew->status ?? '-' }}</span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="viewCrew({{ $crew->id }})" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editCrew({{ $crew->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('crews.destroy', $crew) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus crew ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm btn-icon" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8">
          <div class="empty-state" style="padding:40px 20px">
            <i class="fa-solid fa-video"></i>
            <h3>Belum Ada Crew</h3>
            <p>Tambahkan kru produksi untuk film Anda.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($crews->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $crews->firstItem() }}–{{ $crews->lastItem() }} dari {{ $crews->total() }} crew</span>
    <div class="pagination-btns">
      @if ($crews->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $crews->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($crews->getUrlRange(1, $crews->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $crews->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($crews->hasMorePages())
        <a href="{{ $crews->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Crew Modal -->
<div class="modal-overlay" id="crew-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-user-tie"></i></div>
        <h3 id="crew-modal-title">Tambah Crew Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('crew-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('crews.store') }}" id="crew-form">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-divider">Identitas Crew</div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Nama Crew *</label>
              <input class="form-control" type="text" name="name" placeholder="Nama lengkap" required>
            </div>
            <div class="form-group">
              <label>Posisi</label>
              <input class="form-control" type="text" name="position" placeholder="cth: Kameramen">
            </div>
            <div class="form-group">
              <label>Departemen</label>
              <select class="form-control" name="department">
                <option value="">— Pilih Departemen —</option>
                <option>Sutradara</option>
                <option>Sinematografi</option>
                <option>Produksi</option>
                <option>Artistik</option>
                <option>Suara</option>
                <option>Editing</option>
                <option>VFX</option>
              </select>
            </div>
            <div class="form-group">
              <label>Film *</label>
              <select class="form-control" name="film_id" required>
                <option value="">— Pilih Film —</option>
                @foreach ($films as $film)
                  <option value="{{ $film->id }}">{{ $film->title }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>No. Telepon</label>
              <input class="form-control" type="text" name="phone" placeholder="08xxxxxxxxxx">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input class="form-control" type="email" name="email" placeholder="crew@example.com">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('crew-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Crew</button>
      </div>
    </form>
  </div>
</div>

<!-- View Crew Modal -->
<div class="modal-overlay" id="view-crew-modal">
  <div class="modal" style="max-width:640px">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-eye"></i></div><h3>Detail Crew</h3></div>
      <button class="modal-close" onclick="closeModal('view-crew-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="view-crew-body"></div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('view-crew-modal')">Tutup</button>
      <button class="btn btn-primary" onclick="closeModal('view-crew-modal');openModal('crew-modal')"><i class="fa-solid fa-pen"></i> Edit Crew</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
const crewData = @json($crewData);

function viewCrew(id) {
  const c = crewData[id]; if (!c) return;
  const sc = { Aktif:'badge-green', 'Tidak Aktif':'badge-gray' }[c.status] || 'badge-gray';
  document.getElementById('view-crew-body').innerHTML =
    '<div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:20px"><div style="width:70px;height:70px;border-radius:50%;background:var(--bg-4);display:grid;place-items:center;font-size:28px;color:var(--accent);flex-shrink:0;border:1px solid var(--border)"><i class="fa-solid fa-user-tie"></i></div><div><div style="font-size:20px;font-weight:800;margin-bottom:4px">' + c.nama + '</div><div style="font-size:13px;color:var(--text-2)">' + c.film + ' · ' + c.posisi + '</div><div style="margin-top:8px"><span class="badge ' + sc + '">' + c.status + '</span></div></div></div>' +
    '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px"><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">POSISI</div><div style="font-size:14px;font-weight:600">' + c.posisi + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">DEPARTEMEN</div><div style="font-size:14px;font-weight:600">' + c.departemen + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">TELEPON</div><div style="font-size:14px;font-weight:600">' + c.phone + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">EMAIL</div><div style="font-size:14px;font-weight:600">' + c.email + '</div></div></div>';
  openModal('view-crew-modal');
}

function editCrew(id) {
  const c = crewData[id]; if (!c) return;
  document.getElementById('crew-modal-title').textContent = 'Edit Crew — ' + c.nama;
  const form = document.getElementById('crew-form');
  form.action = '{{ route("crews.index") }}/' + id;
  form.querySelector('input[name="_method"]')?.remove();
  const method = document.createElement('input');
  method.type = 'hidden';
  method.name = '_method';
  method.value = 'PUT';
  form.appendChild(method);
  form.querySelector('[name="name"]').value = c.nama;
  form.querySelector('[name="position"]').value = c.posisi === '-' ? '' : c.posisi;
  form.querySelector('[name="department"]').value = c.departemen === '-' ? '' : c.departemen;
  form.querySelector('[name="film_id"]').value = c.film_id;
  form.querySelector('[name="phone"]').value = c.phone === '-' ? '' : c.phone;
  form.querySelector('[name="email"]').value = c.email === '-' ? '' : c.email;
  openModal('crew-modal');
}
@endpush
