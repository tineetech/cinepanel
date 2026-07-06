@extends('layouts.panel', ['page' => 'shot-lists'])

@section('page_title', 'Kelola Shot List')
@section('page_subtitle', '/ Manajemen Shoot')

@section('content')
<div class="section-header">
  <div>
    <h2>Kelola Shot List</h2>
    <p class="text-muted">Daftar pengambilan gambar per scene</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('shot-lists.export-pdf') }}" class="btn btn-outline" target="_blank">
      <i class="fa-solid fa-file-pdf"></i> PDF
    </a>
    <button class="btn btn-primary" onclick="openModal('shot-list-modal')">
      <i class="fa-solid fa-plus"></i> Tambah Shot
    </button>
  </div>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari shot..." oninput="filterTable(this,'shot-list-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'shot-list-table')">
    <option value="">Semua Status</option>
    <option>Belum</option>
    <option>Proses</option>
    <option>Selesai</option>
    <option>Revisi</option>
  </select>
  <select class="filter-select" onchange="filterByFilm(this,'shot-list-table')">
    <option value="">Semua Film</option>
    @foreach ($films as $film)
      <option value="{{ $film->title }}">{{ $film->title }}</option>
    @endforeach
  </select>
</div>

<div class="table-wrap">
  <table id="shot-list-table">
    <thead>
      <tr>
        <th>#</th><th>Scene</th><th>Shot Ke-</th><th>Deskripsi</th><th>Film</th>
        <th>Tipe Kamera</th><th>Durasi Est</th><th>Lokasi</th><th>Status</th><th>Catatan Sutradara</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($shotLists as $shotList)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>{{ $shotList->scene ?? '-' }}</td>
        <td>{{ $shotList->shot_order ?? '-' }}</td>
        <td>{{ $shotList->shot_description ?? '-' }}</td>
        <td>{{ $shotList->film?->title ?? '-' }}</td>
        <td>{{ $shotList->camera_type ?? '-' }}</td>
        <td>{{ $shotList->estimated_duration ?? '-' }}</td>
        <td>{{ $shotList->location?->name ?? '-' }}</td>
        <td>
          @php
            $sc = match($shotList->status) {
              'Selesai' => 'badge-green',
              'Proses' => 'badge-amber',
              'Revisi' => 'badge-red',
              'Belum' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $shotList->status ?? '-' }}</span>
        </td>
        <td>{{ Str::limit($shotList->director_notes, 30) ?? '-' }}</td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="viewShotList({{ $shotList->id }})" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editShotList({{ $shotList->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('shot-lists.destroy', $shotList) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus shot ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm btn-icon" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="11">
          <div class="empty-state" style="padding:40px 20px">
            <i class="fa-solid fa-list-check"></i>
            <h3>Belum Ada Shot List</h3>
            <p>Tambahkan shot list pertama untuk film Anda.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($shotLists->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $shotLists->firstItem() }}–{{ $shotLists->lastItem() }} dari {{ $shotLists->total() }} shot</span>
    <div class="pagination-btns">
      <form method="GET" action="{{ route('shot-lists.index') }}" style="display:flex;align-items:center;gap:6px;margin-right:8px;font-size:12px;color:var(--text-2)">
        <label>Tampil</label>
        <select name="per_page" onchange="this.form.submit()" style="padding:4px 8px;border-radius:6px;border:1px solid var(--border);background:var(--bg-2);color:var(--text-1);font-size:12px">
          <option value="10" {{ request('per_page', 50) == 10 ? 'selected' : '' }}>10</option>
          <option value="20" {{ request('per_page', 50) == 20 ? 'selected' : '' }}>20</option>
          <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
          <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>100</option>
        </select>
      </form>
      @if ($shotLists->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $shotLists->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($shotLists->getUrlRange(1, $shotLists->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $shotLists->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($shotLists->hasMorePages())
        <a href="{{ $shotLists->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Shot List Modal -->
<div class="modal-overlay" id="shot-list-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-camera"></i></div>
        <h3 id="shot-list-modal-title">Tambah Shot Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('shot-list-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('shot-lists.store') }}" id="shot-list-form">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-divider">Informasi Shot</div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Film *</label>
              <select class="form-control" name="film_id" required>
                <option value="">— Pilih Film —</option>
                @foreach ($films as $film)
                  <option value="{{ $film->id }}" {{ $focusFilm && $focusFilm->id === $film->id ? 'selected' : '' }}>{{ $film->title }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Scene</label>
              <input class="form-control" type="text" name="scene" placeholder="cth: Scene 1">
            </div>
            <div class="form-group">
              <label>Shot Ke-</label>
              <input class="form-control" type="number" name="shot_order" placeholder="cth: 1" min="1">
            </div>
            <div class="form-group">
              <label>Deskripsi Shot</label>
              <input class="form-control" type="text" name="shot_description" placeholder="Deskripsi pengambilan gambar">
            </div>
            <div class="form-group">
              <label>Tipe Kamera</label>
              <select class="form-control" name="camera_type">
                <option value="">— Pilih Tipe —</option>
                <option>Eye Birds View</option>
                <option>Wide Shot</option>
                <option>Medium Shot</option>
                <option>Medium Close Up</option>
                <option>Close Up</option>
                <option>Extreme Close Up</option>
                <option>Two Shot</option>
                <option>Over the Shoulder</option>
                <option>POV</option>
                <option>Low Angle</option>
                <option>High Angle</option>
                <option>Dutch Angle</option>
                <option>Tracking Shot</option>
                <option>Panning Shot</option>
                <option>Tilt Shot</option>
                <option>Insert Shot</option>
                <option>Establishing Shot</option>
                <option>Aerial</option>
                <option>Drone</option>
                <option>First Person</option>
              </select>
            </div>
            <div class="form-group">
              <label>Pergerakan Kamera</label>
              <select class="form-control" name="camera_movement">
                <option value="">— Pilih Gerakan —</option>
                <option>Static</option>
                <option>Pan</option>
                <option>Tilt</option>
                <option>Dolly</option>
                <option>Zoom</option>
                <option>Crab</option>
                <option>Truck</option>
                <option>Pedestal</option>
                <option>Handheld</option>
                <option>Steadicam</option>
                <option>Gimbal</option>
                <option>Slider</option>
                <option>Jib</option>
                <option>Crane</option>
                <option>Drone</option>
                <option>Whip Pan</option>
                <option>Follow</option>
              </select>
            </div>
            <div class="form-group">
              <label>Durasi Estimasi</label>
              <input class="form-control" type="text" name="estimated_duration" placeholder="cth: 5 det">
            </div>
            <div class="form-group">
              <label>Lokasi</label>
              <input class="form-control" type="text" name="location_name" list="location-list" placeholder="Ketik atau pilih lokasi">
              <datalist id="location-list">
                @foreach ($locations as $location)
                  <option value="{{ $location->name }}" data-id="{{ $location->id }}"></option>
                @endforeach
              </datalist>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" name="status">
                <option value="">— Pilih Status —</option>
                <option>Belum</option>
                <option selected>Proses</option>
                <option>Selesai</option>
                <option>Revisi</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Catatan Sutradara</label>
            <textarea class="form-control" name="director_notes" rows="3" placeholder="Catatan sutradara..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('shot-list-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Shot</button>
      </div>
    </form>
  </div>
</div>

<!-- View Shot List Modal -->
<div class="modal-overlay" id="view-shot-list-modal">
  <div class="modal" style="max-width:640px">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-eye"></i></div><h3>Detail Shot</h3></div>
      <button class="modal-close" onclick="closeModal('view-shot-list-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="view-shot-list-body"></div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('view-shot-list-modal')">Tutup</button>
      <button class="btn btn-primary" onclick="closeModal('view-shot-list-modal');openModal('shot-list-modal')"><i class="fa-solid fa-pen"></i> Edit Shot</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
const shotListData = @json($shotListsData);

function viewShotList(id) {
  const s = shotListData[id]; if (!s) return;
  const sc = { Selesai:'badge-green', Proses:'badge-amber', Revisi:'badge-red', Belum:'badge-gray' }[s.status] || 'badge-gray';
  document.getElementById('view-shot-list-body').innerHTML =
    '<div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:20px"><div style="width:70px;height:90px;border-radius:10px;background:var(--bg-4);display:grid;place-items:center;font-size:28px;color:var(--accent);flex-shrink:0;border:1px solid var(--border)"><i class="fa-solid fa-camera"></i></div><div><div style="font-size:20px;font-weight:800;margin-bottom:4px">' + s.scene + ' — Shot ' + s.shot_order + '</div><div style="font-size:13px;color:var(--text-2)">' + s.film + ' · ' + s.kamera + '</div><div style="margin-top:8px"><span class="badge ' + sc + '">' + s.status + '</span></div></div></div>' +
    '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px"><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">TIPE KAMERA</div><div style="font-size:14px;font-weight:600">' + s.kamera + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">GERAKAN</div><div style="font-size:14px;font-weight:600">' + s.gerakan + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">DURASI EST</div><div style="font-size:14px;font-weight:600">' + s.durasi + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">LOKASI</div><div style="font-size:14px;font-weight:600">' + s.lokasi + '</div></div></div>' +
    '<div style="background:var(--bg-3);border-radius:8px;padding:14px"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:8px">CATATAN SUTRADARA</div><div style="font-size:13px;line-height:1.7;color:var(--text-2)">' + s.catatan + '</div></div>';
  openModal('view-shot-list-modal');
}

function editShotList(id) {
  const s = shotListData[id]; if (!s) return;
  document.getElementById('shot-list-modal-title').textContent = 'Edit Shot — ' + s.scene;
  const form = document.getElementById('shot-list-form');
  form.action = '{{ route("shot-lists.index") }}/' + id;
  form.querySelector('input[name="_method"]')?.remove();
  const method = document.createElement('input');
  method.type = 'hidden';
  method.name = '_method';
  method.value = 'PUT';
  form.appendChild(method);
  form.querySelector('[name="film_id"]').value = s.film_id;
  form.querySelector('[name="scene"]').value = s.scene === '-' ? '' : s.scene;
  form.querySelector('[name="shot_order"]').value = s.shot_order === '-' ? '' : s.shot_order;
  form.querySelector('[name="shot_description"]').value = s.deskripsi === '-' ? '' : s.deskripsi;
  form.querySelector('[name="camera_type"]').value = s.kamera === '-' ? '' : s.kamera;
  form.querySelector('[name="camera_movement"]').value = s.gerakan === '-' ? '' : s.gerakan;
  form.querySelector('[name="estimated_duration"]').value = s.durasi === '-' ? '' : s.durasi;
  form.querySelector('[name="location_name"]').value = s.lokasi === '-' ? '' : s.lokasi;
  form.querySelector('[name="status"]').value = s.status || '';
  form.querySelector('[name="director_notes"]').value = s.catatan === '-' ? '' : s.catatan;
  openModal('shot-list-modal');
}
@endpush
