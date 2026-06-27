@extends('layouts.panel', ['page' => 'cast-members'])

@section('page_title', 'Kelola Cast')
@section('page_subtitle', '/ Manajemen Pemeran Film')

@section('content')
<div class="section-header">
  <div>
    <h2>Kelola Cast</h2>
    <p class="text-muted">Manajemen data pemeran film</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('cast-member-modal')">
    <i class="fa-solid fa-plus"></i> Tambah Pemeran
  </button>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari pemeran..." oninput="filterTable(this,'cast-member-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'cast-member-table')">
    <option value="">Semua Status</option>
    <option>Utama</option>
    <option>Pendukung</option>
    <option>Cameo</option>
    <option>Figuran</option>
  </select>
  <select class="filter-select" onchange="filterByFilm(this,'cast-member-table')">
    <option value="">Semua Film</option>
    @foreach ($films as $film)
      <option value="{{ $film->title }}">{{ $film->title }}</option>
    @endforeach
  </select>
</div>

<div class="table-wrap">
  <table id="cast-member-table">
    <thead>
      <tr>
        <th>#</th><th>Pemeran</th><th>Peran</th><th>Film</th>
        <th>Karakter</th><th>Usia</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($castMembers as $castMember)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>
          <div class="avatar-cell">
            <div class="avatar-sm">{{ substr($castMember->name, 0, 2) }}</div>
            {{ $castMember->name }}
          </div>
        </td>
        <td><span class="badge badge-purple">{{ $castMember->role_type ?? '-' }}</span></td>
        <td>{{ $castMember->film?->title ?? '-' }}</td>
        <td>{{ $castMember->character_name ?? '-' }}</td>
        <td>{{ $castMember->age ? $castMember->age . ' thn' : '-' }}</td>
        <td>
          @php
            $sc = match($castMember->status) {
              'Utama' => 'badge-green',
              'Pendukung' => 'badge-amber',
              'Cameo' => 'badge-purple',
              'Figuran' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $castMember->status ?? '-' }}</span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="viewCastMember({{ $castMember->id }})" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editCastMember({{ $castMember->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('cast-members.destroy', $castMember) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus pemeran ini?')">
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
            <i class="fa-solid fa-users"></i>
            <h3>Belum Ada Pemeran</h3>
            <p>Tambahkan pemeran pertama untuk film Anda.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($castMembers->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $castMembers->firstItem() }}–{{ $castMembers->lastItem() }} dari {{ $castMembers->total() }} pemeran</span>
    <div class="pagination-btns">
      @if ($castMembers->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $castMembers->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($castMembers->getUrlRange(1, $castMembers->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $castMembers->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($castMembers->hasMorePages())
        <a href="{{ $castMembers->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Cast Member Modal -->
<div class="modal-overlay" id="cast-member-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-user"></i></div>
        <h3 id="cast-member-modal-title">Tambah Pemeran Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('cast-member-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('cast-members.store') }}" id="cast-member-form">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-divider">Identitas Pemeran</div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Nama Pemeran *</label>
              <input class="form-control" type="text" name="name" placeholder="Nama lengkap" required>
            </div>
            <div class="form-group">
              <label>Nama Karakter</label>
              <input class="form-control" type="text" name="character_name" placeholder="Nama dalam film">
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
              <label>Tipe Peran</label>
              <select class="form-control" name="role_type">
                <option value="">— Pilih Tipe —</option>
                <option>Utama</option>
                <option>Pendukung</option>
                <option>Cameo</option>
                <option>Figuran</option>
              </select>
            </div>
            <div class="form-group">
              <label>Usia</label>
              <input class="form-control" type="number" name="age" placeholder="Usia" min="0" max="200">
            </div>
            <div class="form-group">
              <label>No. Telepon</label>
              <input class="form-control" type="text" name="phone" placeholder="08xxxxxxxxxx">
            </div>
          </div>
          <div class="form-group">
            <label>Catatan</label>
            <textarea class="form-control" name="notes" rows="3" placeholder="Catatan tambahan..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('cast-member-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Pemeran</button>
      </div>
    </form>
  </div>
</div>

<!-- View Cast Member Modal -->
<div class="modal-overlay" id="view-cast-member-modal">
  <div class="modal" style="max-width:640px">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-eye"></i></div><h3>Detail Pemeran</h3></div>
      <button class="modal-close" onclick="closeModal('view-cast-member-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="view-cast-member-body"></div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('view-cast-member-modal')">Tutup</button>
      <button class="btn btn-primary" onclick="closeModal('view-cast-member-modal');openModal('cast-member-modal')"><i class="fa-solid fa-pen"></i> Edit Pemeran</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
const castMemberData = @json($castMemberData);

function viewCastMember(id) {
  const c = castMemberData[id]; if (!c) return;
  const sc = { Utama:'badge-green', Pendukung:'badge-amber', Cameo:'badge-purple', Figuran:'badge-gray' }[c.status] || 'badge-gray';
  document.getElementById('view-cast-member-body').innerHTML =
    '<div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:20px"><div style="width:70px;height:70px;border-radius:50%;background:var(--bg-4);display:grid;place-items:center;font-size:28px;color:var(--accent);flex-shrink:0;border:1px solid var(--border)"><i class="fa-solid fa-user"></i></div><div><div style="font-size:20px;font-weight:800;margin-bottom:4px">' + c.nama + '</div><div style="font-size:13px;color:var(--text-2)">' + c.film + ' · ' + c.peran + '</div><div style="margin-top:8px"><span class="badge ' + sc + '">' + c.status + '</span></div></div></div>' +
    '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px"><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">KARAKTER</div><div style="font-size:14px;font-weight:600">' + c.karakter + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">USIA</div><div style="font-size:14px;font-weight:600">' + c.usia + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">TELEPON</div><div style="font-size:14px;font-weight:600">' + c.phone + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">TIPE PERAN</div><div style="font-size:14px;font-weight:600">' + c.peran + '</div></div></div>' +
    '<div style="background:var(--bg-3);border-radius:8px;padding:14px"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:8px">CATATAN</div><div style="font-size:13px;line-height:1.7;color:var(--text-2)">' + c.catatan + '</div></div>';
  openModal('view-cast-member-modal');
}

function editCastMember(id) {
  const c = castMemberData[id]; if (!c) return;
  document.getElementById('cast-member-modal-title').textContent = 'Edit Pemeran — ' + c.nama;
  const form = document.getElementById('cast-member-form');
  form.action = '{{ route("cast-members.index") }}/' + id;
  form.querySelector('input[name="_method"]')?.remove();
  const method = document.createElement('input');
  method.type = 'hidden';
  method.name = '_method';
  method.value = 'PUT';
  form.appendChild(method);
  form.querySelector('[name="name"]').value = c.nama;
  form.querySelector('[name="character_name"]').value = c.karakter === '-' ? '' : c.karakter;
  form.querySelector('[name="film_id"]').value = c.film_id;
  form.querySelector('[name="role_type"]').value = c.peran === '-' ? '' : c.peran;
  form.querySelector('[name="age"]').value = c.usia === '-' ? '' : c.usia;
  form.querySelector('[name="phone"]').value = c.phone === '-' ? '' : c.phone;
  form.querySelector('[name="notes"]').value = c.catatan === '-' ? '' : c.catatan;
  openModal('cast-member-modal');
}
@endpush
