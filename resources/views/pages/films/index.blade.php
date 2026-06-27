@extends('layouts.panel', ['page' => 'films'])

@section('page_title', 'Kelola Film')
@section('page_subtitle', '/ Manajemen Proyek Film')

@section('content')
<div class="section-header">
  <div>
    <h2>Kelola Film</h2>
    <p class="text-muted">Manajemen data proyek film keseluruhan</p>
  </div>
  <button class="btn btn-primary" onclick="resetFilmForm(); openModal('film-modal')">
    <i class="fa-solid fa-plus"></i> Tambah Film
  </button>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari film..." oninput="filterTable(this,'film-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'film-table')">
    <option value="">Semua Status</option>
    <option>Pra-Produksi</option>
    <option>Produksi</option>
    <option>Pasca-Produksi</option>
    <option>Selesai</option>
  </select>
  <select class="filter-select">
    <option value="">Semua Genre</option>
    <option>Drama</option><option>Aksi</option><option>Thriller</option>
    <option>Komedi</option><option>Horor</option><option>Romantis</option>
  </select>
</div>

<div class="table-wrap">
  <table id="film-table">
    <thead>
      <tr>
        <th>#</th><th>Film</th><th>Genre</th><th>Sutradara</th>
        <th>Tahun</th><th>Anggaran</th><th>Status</th><th>Fokus</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($films as $film)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>
          <div class="film-cell">
            <div class="film-poster">@if ($film->image) <img src="{{ Storage::url($film->image) }}" alt="{{ $film->title }}" style="width:100%;height:100%;object-fit:cover;border-radius:6px"> @else <i class="fa-solid fa-film"></i> @endif</div>
            <div class="film-info">
              <div class="name">{{ $film->title }}</div>
              <div class="sub">{{ $film->subtitle ?? '-' }}</div>
            </div>
          </div>
        </td>
        <td><span class="badge badge-purple">{{ $film->genre }}</span></td>
        <td>
          <div class="avatar-cell">
            <div class="avatar-sm">{{ substr($film->director, 0, 2) }}</div>
            {{ $film->director }}
          </div>
        </td>
        <td>{{ $film->year }}</td>
        <td>Rp {{ $film->budget ? number_format($film->budget, 0, ',', '.') : '-' }}</td>
        <td>
          @php
            $sc = match($film->status) {
              'Produksi' => 'badge-green',
              'Pra-Produksi' => 'badge-amber',
              'Pasca-Produksi' => 'badge-purple',
              'Selesai' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $film->status }}</span>
        </td>
        <td>
          <form method="POST" action="{{ route('films.update', $film) }}" style="display:inline">
            @csrf @method('PUT')
            <input type="hidden" name="is_focus" value="{{ $film->is_focus ? 0 : 1 }}">
            <button type="submit" class="btn btn-ghost btn-sm btn-icon" data-tip="{{ $film->is_focus ? 'Nonaktifkan fokus' : 'Jadikan fokus' }}" style="font-size:16px">
              <i class="fa-solid {{ $film->is_focus ? 'fa-star' : 'fa-star' }}" style="color:{{ $film->is_focus ? 'var(--accent)' : 'var(--text-3)' }}"></i>
            </button>
          </form>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="viewFilm({{ $film->id }})" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editFilm({{ $film->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('films.destroy', $film) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus film ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm btn-icon" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9">
          <div class="empty-state" style="padding:40px 20px">
            <i class="fa-solid fa-clapperboard"></i>
            <h3>Belum Ada Film</h3>
            <p>Tambahkan film pertama Anda untuk memulai.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($films->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $films->firstItem() }}–{{ $films->lastItem() }} dari {{ $films->total() }} film</span>
    <div class="pagination-btns">
      @if ($films->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $films->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($films->getUrlRange(1, $films->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $films->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($films->hasMorePages())
        <a href="{{ $films->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Film Modal -->
<div class="modal-overlay" id="film-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-clapperboard"></i></div>
        <h3 id="film-modal-title">Tambah Film Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('film-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('films.store') }}" id="film-form" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-divider">Informasi Utama</div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Judul Film *</label>
              <input class="form-control" type="text" name="title" placeholder="cth: Fajar di Selatan" required>
            </div>
            <div class="form-group">
              <label>Subtitle / Tagline</label>
              <input class="form-control" type="text" name="subtitle" placeholder="cth: Drama Keluarga">
            </div>
            <div class="form-group">
              <label>Genre *</label>
              <select class="form-control" name="genre">
                <option value="">— Pilih Genre —</option>
                <option>Drama</option><option>Aksi</option><option>Thriller</option>
                <option>Komedi</option><option>Horor</option><option>Romantis</option><option>Dokumenter</option>
              </select>
            </div>
            <div class="form-group">
              <label>Tahun Produksi</label>
              <input class="form-control" type="number" name="year" value="2026" min="2000" max="2099">
            </div>
            <div class="form-group">
              <label>Sutradara *</label>
              <input class="form-control" type="text" name="director" placeholder="Nama sutradara">
            </div>
            <div class="form-group">
              <label>Produser</label>
              <input class="form-control" type="text" name="producer" placeholder="Nama produser">
            </div>
          </div>
          <div class="form-divider">Anggaran & Status</div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Total Anggaran (Rp)</label>
              <input class="form-control" type="text" name="budget" placeholder="cth: 800000000">
            </div>
            <div class="form-group">
              <label>Status Produksi *</label>
              <select class="form-control" name="status">
                <option value="">— Pilih Status —</option>
                <option>Development</option><option>Pra-Produksi</option>
                <option>Produksi</option><option>Pasca-Produksi</option><option>Selesai</option>
              </select>
            </div>
            <div class="form-group">
              <label>Tanggal Mulai</label>
              <input class="form-control" type="date" name="start_date">
            </div>
            <div class="form-group">
              <label>Target Selesai</label>
              <input class="form-control" type="date" name="end_date">
            </div>
            <div class="form-group" style="justify-content:center">
              <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                <input type="checkbox" name="is_focus" value="1" style="width:18px;height:18px;accent-color:var(--accent)">
                Jadikan Film Fokus
              </label>
            </div>
          </div>
          <div class="form-divider">Poster</div>
          <div class="form-group">
            <label>Gambar Poster</label>
            <input class="form-control" type="file" name="image" accept="image/*">
            <small class="text-muted" style="font-size:11px">Format: JPEG, PNG, WebP. Maks 2MB</small>
          </div>
          <div class="form-group">
            <label>Sinopsis</label>
            <textarea class="form-control" name="synopsis" rows="3" placeholder="Deskripsi singkat tentang film..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('film-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Film</button>
      </div>
    </form>
  </div>
</div>

<!-- View Film Modal -->
<div class="modal-overlay" id="view-film-modal">
  <div class="modal" style="max-width:640px">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-eye"></i></div><h3>Detail Film</h3></div>
      <button class="modal-close" onclick="closeModal('view-film-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="view-film-body"></div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('view-film-modal')">Tutup</button>
      <button class="btn btn-primary" onclick="closeModal('view-film-modal');openModal('film-modal')"><i class="fa-solid fa-pen"></i> Edit Film</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
const filmData = @json($filmData);

function resetFilmForm() {
  document.getElementById('film-modal-title').textContent = 'Tambah Film Baru';
  const form = document.getElementById('film-form');
  form.action = '{{ route("films.store") }}';
  const methodInput = form.querySelector('input[name="_method"]');
  if (methodInput) methodInput.remove();
  form.reset();
}

function viewFilm(id) {
  const f = filmData[id]; if (!f) return;
  const sc = { Produksi:'badge-green', 'Pra-Produksi':'badge-amber', 'Pasca-Produksi':'badge-purple', Development:'badge-gray', Selesai:'badge-gray' }[f.status] || 'badge-gray';
  document.getElementById('view-film-body').innerHTML =
    '<div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:20px"><div style="width:70px;height:90px;border-radius:10px;background:var(--bg-4);display:grid;place-items:center;font-size:28px;color:var(--accent);flex-shrink:0;border:1px solid var(--border);overflow:hidden">' + (f.image ? '<img src="' + f.image + '" style="width:100%;height:100%;object-fit:cover">' : '<i class="fa-solid fa-film"></i>') + '</div><div><div style="font-size:20px;font-weight:800;margin-bottom:4px">' + f.judul + '</div><div style="font-size:13px;color:var(--text-2)">' + f.genre + ' · ' + f.tahun + '</div><div style="margin-top:8px"><span class="badge ' + sc + '">' + f.status + '</span></div></div></div>' +
    '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px"><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">SUTRADARA</div><div style="font-size:14px;font-weight:600">' + f.sutradara + '</div></div><div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">TOTAL ANGGARAN</div><div style="font-size:14px;font-weight:600">' + f.anggaran + '</div></div></div>' +
    '<div style="background:var(--bg-3);border-radius:8px;padding:14px"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:8px">SINOPSIS</div><div style="font-size:13px;line-height:1.7;color:var(--text-2)">' + f.sinopsis + '</div></div>';
  openModal('view-film-modal');
}

function editFilm(id) {
  const f = filmData[id]; if (!f) return;
  document.getElementById('film-modal-title').textContent = 'Edit Film — ' + f.judul;
  const form = document.getElementById('film-form');
  form.action = '/films/' + id;
  let methodInput = form.querySelector('input[name="_method"]');
  if (!methodInput) { methodInput = document.createElement('input'); methodInput.type = 'hidden'; methodInput.name = '_method'; form.appendChild(methodInput); }
  methodInput.value = 'PUT';
  form.querySelector('[name="title"]').value = f.judul;
  form.querySelector('[name="genre"]').value = f.genre;
  form.querySelector('[name="director"]').value = f.sutradara;
  form.querySelector('[name="year"]').value = f.tahun;
  form.querySelector('[name="synopsis"]').value = f.sinopsis;
  form.querySelector('[name="is_focus"]').checked = f.is_focus;
  openModal('film-modal');
}
@endpush
