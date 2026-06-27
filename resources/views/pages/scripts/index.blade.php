@extends('layouts.panel', ['page' => 'scripts'])

@section('page_title', 'Kelola Skenario')
@section('page_subtitle', '/ Manajemen Naskah Film')

@section('content')
<div class="section-header">
  <div>
    <h2>Kelola Skenario</h2>
    <p class="text-muted">Manajemen data skrip dan naskah film</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('script-modal')">
    <i class="fa-solid fa-plus"></i> Tambah Skenario
  </button>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari skenario..." oninput="filterTable(this,'script-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'script-table')">
    <option value="">Semua Status</option>
    <option>Draft</option>
    <option>Revisi</option>
    <option>Final</option>
  </select>
  <select class="filter-select" onchange="filterByFilm(this,'script-table')">
    <option value="">Semua Film</option>
    @foreach ($films as $film)
      <option value="{{ $film->title }}">{{ $film->title }}</option>
    @endforeach
  </select>
</div>

<div class="table-wrap">
  <table id="script-table">
    <thead>
      <tr>
        <th>#</th><th>Judul</th><th>Film</th><th>Penulis</th>
        <th>Versi</th><th>Halaman</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($scripts as $script)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>
          <div class="film-cell">
            <div class="film-poster"><i class="fa-solid fa-scroll"></i></div>
            <div class="film-info">
              <div class="name">{{ $script->title }}</div>
            </div>
          </div>
        </td>
        <td>{{ $script->film?->title ?? '-' }}</td>
        <td>{{ $script->writer ?? '-' }}</td>
        <td>{{ $script->version ?? '-' }}</td>
        <td>{{ $script->page_count ? $script->page_count . ' hal' : '-' }}</td>
        <td>
          @php
            $sc = match($script->status) {
              'Final' => 'badge-green',
              'Revisi' => 'badge-amber',
              'Draft' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $script->status ?? '-' }}</span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="viewScript({{ $script->id }})" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editScript({{ $script->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('scripts.destroy', $script) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus skenario ini?')">
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
            <i class="fa-solid fa-scroll"></i>
            <h3>Belum Ada Skenario</h3>
            <p>Tambahkan skenario pertama untuk film Anda.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($scripts->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $scripts->firstItem() }}–{{ $scripts->lastItem() }} dari {{ $scripts->total() }} skenario</span>
    <div class="pagination-btns">
      @if ($scripts->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $scripts->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($scripts->getUrlRange(1, $scripts->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $scripts->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($scripts->hasMorePages())
        <a href="{{ $scripts->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Script Modal -->
<div class="modal-overlay" id="script-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-scroll"></i></div>
        <h3 id="script-modal-title">Tambah Skenario Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('script-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('scripts.store') }}" id="script-form">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-divider">Informasi Skenario</div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Judul Skenario *</label>
              <input class="form-control" type="text" name="title" placeholder="cth: Skenario Utama" required>
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
              <label>Penulis</label>
              <input class="form-control" type="text" name="writer" placeholder="Nama penulis skenario">
            </div>
            <div class="form-group">
              <label>Versi</label>
              <input class="form-control" type="text" name="version" placeholder="cth: v3.2">
            </div>
            <div class="form-group">
              <label>Jumlah Halaman</label>
              <input class="form-control" type="number" name="page_count" placeholder="cth: 98" min="1">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" name="status">
                <option value="">— Pilih Status —</option>
                <option>Draft</option>
                <option>Revisi</option>
                <option>Final</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Catatan Revisi</label>
            <textarea class="form-control" name="revision_notes" rows="3" placeholder="Catatan revisi..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('script-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Skenario</button>
      </div>
    </form>
  </div>
</div>

<!-- View Script Modal -->
<div class="modal-overlay" id="view-script-modal">
  <div class="modal" style="max-width:640px">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-eye"></i></div><h3>Detail Skenario</h3></div>
      <button class="modal-close" onclick="closeModal('view-script-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="view-script-body"></div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('view-script-modal')">Tutup</button>
      <button class="btn btn-primary" onclick="closeModal('view-script-modal');openModal('script-modal')"><i class="fa-solid fa-pen"></i> Edit Skenario</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
const scriptData = @json($scriptsData);

function viewScript(id) {
  const s = scriptData[id]; if (!s) return;
  const sc = { Final:'badge-green', Revisi:'badge-amber', Draft:'badge-gray' }[s.status] || 'badge-gray';
  document.getElementById('view-script-body').innerHTML =
    '<div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:20px"><div style="width:70px;height:90px;border-radius:10px;background:var(--bg-4);display:grid;place-items:center;font-size:28px;color:var(--accent);flex-shrink:0;border:1px solid var(--border)"><i class="fa-solid fa-scroll"></i></div><div><div style="font-size:20px;font-weight:800;margin-bottom:4px">' + s.judul + '</div><div style="font-size:13px;color:var(--text-2)">' + s.film + ' · Versi ' + s.versi + '</div><div style="margin-top:8px"><span class="badge ' + sc + '">' + s.status + '</span></div></div></div>' +
    '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px"><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">PENULIS</div><div style="font-size:14px;font-weight:600">' + s.penulis + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">HALAMAN</div><div style="font-size:14px;font-weight:600">' + (s.halaman !== '-' ? s.halaman + ' hal' : '-') + '</div></div></div>' +
    '<div style="background:var(--bg-3);border-radius:8px;padding:14px"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:8px">CATATAN REVISI</div><div style="font-size:13px;line-height:1.7;color:var(--text-2)">' + s.catatan + '</div></div>';
  openModal('view-script-modal');
}

function editScript(id) {
  const s = scriptData[id]; if (!s) return;
  document.getElementById('script-modal-title').textContent = 'Edit Skenario — ' + s.judul;
  const form = document.getElementById('script-form');
  form.action = '{{ route("scripts.index") }}/' + id;
  form.querySelector('input[name="_method"]')?.remove();
  const method = document.createElement('input');
  method.type = 'hidden';
  method.name = '_method';
  method.value = 'PUT';
  form.appendChild(method);
  form.querySelector('[name="title"]').value = s.judul;
  form.querySelector('[name="film_id"]').value = s.film_id;
  form.querySelector('[name="writer"]').value = s.penulis === '-' ? '' : s.penulis;
  form.querySelector('[name="version"]').value = s.versi === '-' ? '' : s.versi;
  form.querySelector('[name="page_count"]').value = s.halaman === '-' ? '' : s.halaman;
  form.querySelector('[name="status"]').value = s.status || '';
  form.querySelector('[name="revision_notes"]').value = s.catatan === '-' ? '' : s.catatan;
  openModal('script-modal');
}
@endpush
