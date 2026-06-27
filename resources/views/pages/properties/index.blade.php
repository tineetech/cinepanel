@extends('layouts.panel', ['page' => 'properties'])

@section('page_title', 'Kelola Properti')
@section('page_subtitle', '/ Manajemen Perlengkapan Film')

@section('content')
<div class="section-header">
  <div>
    <h2>Kelola Properti</h2>
    <p class="text-muted">Manajemen data properti dan perlengkapan film</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('property-modal')">
    <i class="fa-solid fa-plus"></i> Tambah Properti
  </button>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari properti..." oninput="filterTable(this,'property-table')">
  </div>
  <select class="filter-select" onchange="filterByKategori(this,'property-table')">
    <option value="">Semua Kategori</option>
    <option>Kostum</option>
    <option>Set Dekorasi</option>
    <option>Peralatan Teknis</option>
    <option>Kendaraan</option>
    <option>Senjata Replika</option>
  </select>
  <select class="filter-select" onchange="filterByStatus(this,'property-table')">
    <option value="">Semua Status</option>
    <option>Dicari</option>
    <option>Proses</option>
    <option>Tersedia</option>
    <option>Selesai</option>
  </select>
</div>

<div class="table-wrap">
  <table id="property-table">
    <thead>
      <tr>
        <th>#</th><th>Nama Properti</th><th>Kategori</th><th>Film</th>
        <th>Jumlah</th><th>Estimasi Harga</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($properties as $property)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>{{ $property->name }}</td>
        <td><span class="badge badge-purple">{{ $property->category ?? '-' }}</span></td>
        <td>{{ $property->film?->title ?? '-' }}</td>
        <td>{{ $property->quantity ? $property->quantity . ' ' . $property->unit : '-' }}</td>
        <td>Rp {{ $property->estimated_price ? number_format($property->estimated_price, 0, ',', '.') : '-' }}</td>
        <td>
          @php
            $sc = match($property->status) {
              'Tersedia' => 'badge-green',
              'Proses' => 'badge-amber',
              'Dicari' => 'badge-purple',
              'Selesai' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $property->status ?? '-' }}</span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="viewProperty({{ $property->id }})" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editProperty({{ $property->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('properties.destroy', $property) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus properti ini?')">
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
            <i class="fa-solid fa-box"></i>
            <h3>Belum Ada Properti</h3>
            <p>Tambahkan properti pertama untuk film Anda.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($properties->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $properties->firstItem() }}–{{ $properties->lastItem() }} dari {{ $properties->total() }} properti</span>
    <div class="pagination-btns">
      @if ($properties->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $properties->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $properties->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($properties->hasMorePages())
        <a href="{{ $properties->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Property Modal -->
<div class="modal-overlay" id="property-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-box"></i></div>
        <h3 id="property-modal-title">Tambah Properti Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('property-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('properties.store') }}" id="property-form">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-divider">Informasi Properti</div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Nama Properti *</label>
              <input class="form-control" type="text" name="name" placeholder="cth: Kostum Pahlawan" required>
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select class="form-control" name="category">
                <option value="">— Pilih Kategori —</option>
                <option>Kostum</option>
                <option>Set Dekorasi</option>
                <option>Peralatan Teknis</option>
                <option>Kendaraan</option>
                <option>Senjata Replika</option>
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
              <label>Jumlah</label>
              <input class="form-control" type="number" name="quantity" placeholder="0" min="0">
            </div>
            <div class="form-group">
              <label>Satuan</label>
              <input class="form-control" type="text" name="unit" placeholder="cth: buah, set, unit">
            </div>
            <div class="form-group">
              <label>Estimasi Harga (Rp)</label>
              <input class="form-control" type="text" name="estimated_price" placeholder="cth: 5000000">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" name="status">
                <option value="">— Pilih Status —</option>
                <option>Dicari</option>
                <option>Proses</option>
                <option>Tersedia</option>
                <option>Selesai</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Catatan</label>
            <textarea class="form-control" name="notes" rows="3" placeholder="Deskripsi atau catatan properti..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('property-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Properti</button>
      </div>
    </form>
  </div>
</div>

<!-- View Property Modal -->
<div class="modal-overlay" id="view-property-modal">
  <div class="modal" style="max-width:640px">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-eye"></i></div><h3>Detail Properti</h3></div>
      <button class="modal-close" onclick="closeModal('view-property-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="view-property-body"></div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('view-property-modal')">Tutup</button>
      <button class="btn btn-primary" onclick="closeModal('view-property-modal');openModal('property-modal')"><i class="fa-solid fa-pen"></i> Edit Properti</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
const propertyData = @json($propertyData);

function viewProperty(id) {
  const p = propertyData[id]; if (!p) return;
  const sc = { Tersedia:'badge-green', Proses:'badge-amber', Dicari:'badge-purple', Selesai:'badge-gray' }[p.status] || 'badge-gray';
  document.getElementById('view-property-body').innerHTML =
    '<div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:20px"><div style="width:70px;height:70px;border-radius:10px;background:var(--bg-4);display:grid;place-items:center;font-size:28px;color:var(--accent);flex-shrink:0;border:1px solid var(--border)"><i class="fa-solid fa-box"></i></div><div><div style="font-size:20px;font-weight:800;margin-bottom:4px">' + p.nama + '</div><div style="font-size:13px;color:var(--text-2)">' + p.kategori + ' · ' + p.film + '</div><div style="margin-top:8px"><span class="badge ' + sc + '">' + p.status + '</span></div></div></div>' +
    '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px"><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">KATEGORI</div><div style="font-size:14px;font-weight:600">' + p.kategori + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">JUMLAH</div><div style="font-size:14px;font-weight:600">' + p.jumlah + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">ESTIMASI HARGA</div><div style="font-size:14px;font-weight:600">' + p.estimasi + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">STATUS</div><div style="font-size:14px;font-weight:600">' + p.status + '</div></div></div>' +
    '<div style="background:var(--bg-3);border-radius:8px;padding:14px"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:8px">CATATAN</div><div style="font-size:13px;line-height:1.7;color:var(--text-2)">' + p.catatan + '</div></div>';
  openModal('view-property-modal');
}

function editProperty(id) {
  const p = propertyData[id]; if (!p) return;
  document.getElementById('property-modal-title').textContent = 'Edit Properti — ' + p.nama;
  const form = document.getElementById('property-form');
  form.action = '{{ route("properties.index") }}/' + id;
  form.querySelector('input[name="_method"]')?.remove();
  const method = document.createElement('input');
  method.type = 'hidden';
  method.name = '_method';
  method.value = 'PUT';
  form.appendChild(method);
  form.querySelector('[name="name"]').value = p.nama;
  form.querySelector('[name="category"]').value = p.kategori === '-' ? '' : p.kategori;
  form.querySelector('[name="film_id"]').value = p.film_id;
  form.querySelector('[name="quantity"]').value = p.quantity ?? '';
  form.querySelector('[name="unit"]').value = p.unit;
  form.querySelector('[name="estimated_price"]').value = p.estimated_price;
  form.querySelector('[name="status"]').value = p.status ?? '';
  form.querySelector('[name="notes"]').value = p.catatan === '-' ? '' : p.catatan;
  openModal('property-modal');
}
@endpush
