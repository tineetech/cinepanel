@extends('layouts.panel', ['page' => 'locations'])

@section('page_title', 'Kelola Lokasi')
@section('page_subtitle', '/ Manajemen Lokasi Syuting')

@section('content')
<div class="section-header">
  <div>
    <h2>Kelola Lokasi</h2>
    <p class="text-muted">Manajemen lokasi syuting produksi film</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('location-modal')">
    <i class="fa-solid fa-plus"></i> Tambah Lokasi
  </button>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari lokasi..." oninput="filterTable(this,'location-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'location-table')">
    <option value="">Semua Status</option>
    <option>Survey</option>
    <option>Negosiasi</option>
    <option>Konfirmasi</option>
    <option>Selesai</option>
  </select>
  <select class="filter-select" onchange="filterByStatus(this,'location-table')">
    <option value="">Semua Tipe</option>
    <option>Interior</option>
    <option>Eksterior</option>
    <option>Studio</option>
  </select>
</div>

<div class="table-wrap">
  <table id="location-table">
    <thead>
      <tr>
        <th>#</th><th>Nama</th><th>Tipe</th><th>Alamat</th>
        <th>Film</th><th>Tanggal Syuting</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($locations as $loc)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>
          <div class="film-cell">
            <div class="film-poster"><i class="fa-solid fa-map-pin"></i></div>
            <div class="film-info">
              <div class="name">{{ $loc->name }}</div>
              @if ($loc->notes)
              <div class="sub">{{ Str::limit($loc->notes, 40) }}</div>
              @endif
            </div>
          </div>
        </td>
        <td>
          @php
            $tc = match($loc->type) {
              'Interior' => 'badge-purple',
              'Eksterior' => 'badge-green',
              'Studio' => 'badge-blue',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $tc }}">{{ $loc->type }}</span>
        </td>
        <td>{{ Str::limit($loc->address, 30) }}</td>
        <td>{{ $loc->film->title ?? '-' }}</td>
        <td>
          @if ($loc->start_date && $loc->end_date)
            {{ $loc->start_date->format('d/m/Y') }} - {{ $loc->end_date->format('d/m/Y') }}
          @elseif ($loc->start_date)
            {{ $loc->start_date->format('d/m/Y') }}
          @else
            -
          @endif
        </td>
        <td>
          @php
            $sc = match($loc->status) {
              'Konfirmasi' => 'badge-green',
              'Negosiasi' => 'badge-amber',
              'Survey' => 'badge-blue',
              'Selesai' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $loc->status }}</span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editLocation({{ $loc->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('locations.destroy', $loc) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
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
            <i class="fa-solid fa-map-location-dot"></i>
            <h3>Belum Ada Lokasi</h3>
            <p>Tambahkan lokasi syuting untuk produksi film.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($locations->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $locations->firstItem() }}–{{ $locations->lastItem() }} dari {{ $locations->total() }} lokasi</span>
    <div class="pagination-btns">
      @if ($locations->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $locations->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($locations->getUrlRange(1, $locations->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $locations->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($locations->hasMorePages())
        <a href="{{ $locations->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Location Modal -->
<div class="modal-overlay" id="location-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-map-location-dot"></i></div>
        <h3 id="location-modal-title">Tambah Lokasi Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('location-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('locations.store') }}" id="location-form">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
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
            <label>Nama Lokasi *</label>
            <input class="form-control" type="text" name="name" placeholder="cth: Gedung Merdeka" required>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Tipe *</label>
              <select class="form-control" name="type" required>
                <option value="">— Pilih Tipe —</option>
                <option>Interior</option><option>Eksterior</option><option>Studio</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status *</label>
              <select class="form-control" name="status" required>
                <option value="">— Pilih Status —</option>
                <option>Survey</option><option>Negosiasi</option><option>Konfirmasi</option><option>Selesai</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Alamat *</label>
            <textarea class="form-control" name="address" rows="2" placeholder="Alamat lengkap lokasi" required></textarea>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Tanggal Mulai</label>
              <input class="form-control" type="date" name="start_date">
            </div>
            <div class="form-group">
              <label>Tanggal Selesai</label>
              <input class="form-control" type="date" name="end_date">
            </div>
          </div>
          <div class="form-group">
            <label>Biaya Sewa (Rp)</label>
            <input class="form-control" type="text" name="rental_cost" placeholder="cth: 15000000">
          </div>
          <div class="form-group">
            <label>Catatan</label>
            <textarea class="form-control" name="notes" rows="2" placeholder="Catatan tambahan..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('location-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Lokasi</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
const locationData = @json($locationData);

function editLocation(id) {
  const l = locationData[id]; if (!l) return;
  document.getElementById('location-modal-title').textContent = 'Edit Lokasi — ' + l.name;
  const form = document.getElementById('location-form');
  form.action = '{{ route("locations.index") }}/' + id;
  form.querySelector('input[name="_method"]')?.remove();
  const method = document.createElement('input');
  method.type = 'hidden'; method.name = '_method'; method.value = 'PUT';
  form.appendChild(method);
  form.querySelector('[name="name"]').value = l.name;
  form.querySelector('[name="film_id"]').value = l.film_id;
  form.querySelector('[name="type"]').value = l.type;
  form.querySelector('[name="address"]').value = l.address;
  form.querySelector('[name="start_date"]').value = l.start_date ?? '';
  form.querySelector('[name="end_date"]').value = l.end_date ?? '';
  form.querySelector('[name="rental_cost"]').value = l.rental_cost ?? '';
  form.querySelector('[name="status"]').value = l.status;
  form.querySelector('[name="notes"]').value = l.notes ?? '';
  openModal('location-modal');
}
@endpush
