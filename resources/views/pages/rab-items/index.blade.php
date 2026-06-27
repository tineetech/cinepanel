@extends('layouts.panel', ['page' => 'rab-items'])

@section('page_title', 'Kelola RAB')
@section('page_subtitle', '/ Anggaran Produksi')

@section('content')
<div class="section-header">
  <div>
    <h2>Kelola RAB</h2>
    <p class="text-muted">Manajemen item anggaran produksi film</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('rab-item-modal')">
    <i class="fa-solid fa-plus"></i> Tambah Item
  </button>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari item RAB..." oninput="filterTable(this,'rab-item-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'rab-item-table')">
    <option value="">Semua Status</option>
    <option>Draft</option>
    <option>Review</option>
    <option>Disetujui</option>
    <option>Ditolak</option>
  </select>
  <select class="filter-select" onchange="filterByStatus(this,'rab-item-table')">
    <option value="">Semua Kategori</option>
    <option>Lokasi</option><option>SDM</option><option>Peralatan</option>
    <option>Konsumsi</option><option>Transportasi</option><option>Marketing</option><option>Lain-lain</option>
  </select>
</div>

<div class="table-wrap">
  <table id="rab-item-table">
    <thead>
      <tr>
        <th>#</th><th>Nama Item</th><th>Kategori</th><th>Film</th>
        <th>Qty</th><th>Total</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($rabItems as $item)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>
          <div class="film-cell">
            <div class="film-poster"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <div class="film-info">
              <div class="name">{{ $item->name }}</div>
              @if ($item->notes)
              <div class="sub">{{ Str::limit($item->notes, 40) }}</div>
              @endif
            </div>
          </div>
        </td>
        <td>
          @php
            $kc = match($item->category) {
              'Lokasi' => 'badge-blue',
              'SDM' => 'badge-purple',
              'Peralatan' => 'badge-amber',
              'Konsumsi' => 'badge-green',
              'Transportasi' => 'badge-red',
              'Marketing' => 'badge-purple',
              'Lain-lain' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $kc }}">{{ $item->category }}</span>
        </td>
        <td>{{ $item->film->title ?? '-' }}</td>
        <td>{{ $item->quantity }} {{ $item->unit }}</td>
        <td>Rp {{ $item->total_price ? number_format($item->total_price, 0, ',', '.') : '-' }}</td>
        <td>
          @php
            $sc = match($item->status) {
              'Disetujui' => 'badge-green',
              'Review' => 'badge-amber',
              'Draft' => 'badge-gray',
              'Ditolak' => 'badge-red',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $item->status }}</span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editRabItem({{ $item->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('rab-items.destroy', $item) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus item RAB ini?')">
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
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <h3>Belum Ada Item RAB</h3>
            <p>Tambahkan item anggaran pertama untuk produksi film.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($rabItems->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $rabItems->firstItem() }}–{{ $rabItems->lastItem() }} dari {{ $rabItems->total() }} item</span>
    <div class="pagination-btns">
      @if ($rabItems->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $rabItems->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($rabItems->getUrlRange(1, $rabItems->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $rabItems->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($rabItems->hasMorePages())
        <a href="{{ $rabItems->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Rab Item Modal -->
<div class="modal-overlay" id="rab-item-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
        <h3 id="rab-item-modal-title">Tambah Item RAB</h3>
      </div>
      <button class="modal-close" onclick="closeModal('rab-item-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('rab-items.store') }}" id="rab-item-form">
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
            <label>Nama Item *</label>
            <input class="form-control" type="text" name="name" placeholder="cth: Sewa Kamera" required>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Kategori *</label>
              <select class="form-control" name="category" required>
                <option value="">— Pilih Kategori —</option>
                <option>Lokasi</option><option>SDM</option><option>Peralatan</option>
                <option>Konsumsi</option><option>Transportasi</option><option>Marketing</option><option>Lain-lain</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status *</label>
              <select class="form-control" name="status" required>
                <option value="">— Pilih Status —</option>
                <option>Draft</option><option>Review</option><option>Disetujui</option><option>Ditolak</option>
              </select>
            </div>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Jumlah *</label>
              <input class="form-control" type="number" name="quantity" min="1" placeholder="cth: 5" required>
            </div>
            <div class="form-group">
              <label>Satuan *</label>
              <input class="form-control" type="text" name="unit" placeholder="cth: unit, hari, paket" required>
            </div>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Harga Satuan (Rp) *</label>
              <input class="form-control" type="text" name="unit_price" placeholder="cth: 500000" required>
            </div>
            <div class="form-group">
              <label>Total Harga (Rp)</label>
              <input class="form-control" type="text" name="total_price" placeholder="Kosongkan jika otomatis">
            </div>
          </div>
          <div class="form-group">
            <label>Catatan</label>
            <textarea class="form-control" name="notes" rows="2" placeholder="Catatan tambahan..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('rab-item-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Item</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
const rabItemData = @json($rabItemData);

let rabItemFormAction = '{{ route("rab-items.store") }}';

function editRabItem(id) {
  const i = rabItemData[id]; if (!i) return;
  document.getElementById('rab-item-modal-title').textContent = 'Edit Item — ' + i.name;
  const form = document.getElementById('rab-item-form');
  form.action = '{{ route("rab-items.index") }}/' + id;
  form.querySelector('input[name="_method"]')?.remove();
  const method = document.createElement('input');
  method.type = 'hidden'; method.name = '_method'; method.value = 'PUT';
  form.appendChild(method);
  form.querySelector('[name="name"]').value = i.name;
  form.querySelector('[name="film_id"]').value = i.film_id;
  form.querySelector('[name="category"]').value = i.category;
  form.querySelector('[name="quantity"]').value = i.quantity;
  form.querySelector('[name="unit"]').value = i.unit;
  form.querySelector('[name="unit_price"]').value = i.unit_price;
  form.querySelector('[name="total_price"]').value = i.total_price;
  form.querySelector('[name="status"]').value = i.status;
  form.querySelector('[name="notes"]').value = i.notes ?? '';
  openModal('rab-item-modal');
}
@endpush
