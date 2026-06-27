@extends('layouts.panel', ['page' => 'schedules'])

@section('page_title', 'Jadwal Produksi')
@section('page_subtitle', '/ Manajemen Jadwal')

@section('content')
<div class="section-header">
  <div>
    <h2>Jadwal Produksi</h2>
    <p class="text-muted">Manajemen jadwal kegiatan produksi film</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('schedule-modal')">
    <i class="fa-solid fa-plus"></i> Tambah Jadwal
  </button>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari jadwal..." oninput="filterTable(this,'schedule-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'schedule-table')">
    <option value="">Semua Status</option>
    <option>Terjadwal</option>
    <option>Upcoming</option>
    <option>Berlangsung</option>
    <option>Selesai</option>
    <option>Dibatalkan</option>
  </select>
  <select class="filter-select" onchange="filterByStatus(this,'schedule-table')">
    <option value="">Semua Tipe</option>
    <option>Meeting</option><option>Syuting</option><option>Casting</option>
    <option>Review</option><option>Training</option><option>Lainnya</option>
  </select>
</div>

<div class="table-wrap">
  <table id="schedule-table">
    <thead>
      <tr>
        <th>#</th><th>Kegiatan</th><th>Tipe</th><th>Film</th>
        <th>Tanggal</th><th>Waktu</th><th>Lokasi</th><th>Status</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($schedules as $sched)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>
          <div class="film-cell">
            <div class="film-poster"><i class="fa-solid fa-calendar-day"></i></div>
            <div class="film-info">
              <div class="name">{{ $sched->activity_name }}</div>
              @if ($sched->pic)
              <div class="sub">PIC: {{ $sched->pic }}</div>
              @endif
            </div>
          </div>
        </td>
        <td>
          @php
            $tc = match($sched->activity_type) {
              'Meeting' => 'badge-purple',
              'Syuting' => 'badge-red',
              'Casting' => 'badge-blue',
              'Review' => 'badge-amber',
              'Training' => 'badge-green',
              'Lainnya' => 'badge-gray',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $tc }}">{{ $sched->activity_type }}</span>
        </td>
        <td>{{ $sched->film->title ?? '-' }}</td>
        <td>{{ $sched->date ? $sched->date->format('d/m/Y') : '-' }}</td>
        <td>
          @if ($sched->start_time && $sched->end_time)
            {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}
          @elseif ($sched->start_time)
            {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}
          @else
            -
          @endif
        </td>
        <td>{{ $sched->location ?? '-' }}</td>
        <td>
          @php
            $sc = match($sched->status) {
              'Selesai' => 'badge-green',
              'Upcoming' => 'badge-blue',
              'Berlangsung' => 'badge-amber',
              'Terjadwal' => 'badge-gray',
              'Dibatalkan' => 'badge-red',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $sched->status }}</span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-ghost btn-sm btn-icon" onclick="editSchedule({{ $sched->id }})" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('schedules.destroy', $sched) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
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
            <i class="fa-solid fa-calendar-days"></i>
            <h3>Belum Ada Jadwal</h3>
            <p>Tambahkan jadwal kegiatan produksi film.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($schedules->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }} dari {{ $schedules->total() }} jadwal</span>
    <div class="pagination-btns">
      @if ($schedules->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $schedules->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($schedules->getUrlRange(1, $schedules->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $schedules->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($schedules->hasMorePages())
        <a href="{{ $schedules->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Schedule Modal -->
<div class="modal-overlay" id="schedule-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-calendar-days"></i></div>
        <h3 id="schedule-modal-title">Tambah Jadwal Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('schedule-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form method="POST" action="{{ route('schedules.store') }}" id="schedule-form">
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
            <label>Nama Kegiatan *</label>
            <input class="form-control" type="text" name="activity_name" placeholder="cth: Syuting Adegan 1" required>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Tipe Kegiatan *</label>
              <select class="form-control" name="activity_type" required>
                <option value="">— Pilih Tipe —</option>
                <option>Meeting</option><option>Syuting</option><option>Casting</option>
                <option>Review</option><option>Training</option><option>Lainnya</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status *</label>
              <select class="form-control" name="status" required>
                <option value="">— Pilih Status —</option>
                <option>Terjadwal</option><option>Upcoming</option>
                <option>Berlangsung</option><option>Selesai</option><option>Dibatalkan</option>
              </select>
            </div>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Tanggal *</label>
              <input class="form-control" type="date" name="date" required>
            </div>
            <div class="form-group">
              <label>Lokasi</label>
              <input class="form-control" type="text" name="location" placeholder="cth: Gedung Serbaguna">
            </div>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>Jam Mulai</label>
              <input class="form-control" type="time" name="start_time">
            </div>
            <div class="form-group">
              <label>Jam Selesai</label>
              <input class="form-control" type="time" name="end_time">
            </div>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label>PIC</label>
              <input class="form-control" type="text" name="pic" placeholder="Nama penanggung jawab">
            </div>
            <div class="form-group">
              <label>Peserta</label>
              <input class="form-control" type="text" name="attendees" placeholder="cth: Budi, Ani, Rudi">
            </div>
          </div>
          <div class="form-group">
            <label>Materi Diskusi</label>
            <textarea class="form-control" name="discussion_materials" rows="2" placeholder="Materi yang akan dibahas..."></textarea>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea class="form-control" name="description" rows="2" placeholder="Deskripsi kegiatan..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('schedule-modal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Jadwal</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
const scheduleData = @json($scheduleData);

function editSchedule(id) {
  const s = scheduleData[id]; if (!s) return;
  document.getElementById('schedule-modal-title').textContent = 'Edit Jadwal — ' + s.activity_name;
  const form = document.getElementById('schedule-form');
  form.action = '{{ route("schedules.index") }}/' + id;
  form.querySelector('input[name="_method"]')?.remove();
  const method = document.createElement('input');
  method.type = 'hidden'; method.name = '_method'; method.value = 'PUT';
  form.appendChild(method);
  form.querySelector('[name="activity_name"]').value = s.activity_name;
  form.querySelector('[name="film_id"]').value = s.film_id;
  form.querySelector('[name="activity_type"]').value = s.activity_type;
  form.querySelector('[name="date"]').value = s.date ?? '';
  form.querySelector('[name="start_time"]').value = s.start_time ?? '';
  form.querySelector('[name="end_time"]').value = s.end_time ?? '';
  form.querySelector('[name="location"]').value = s.location ?? '';
  form.querySelector('[name="pic"]').value = s.pic ?? '';
  form.querySelector('[name="attendees"]').value = s.attendees ?? '';
  form.querySelector('[name="discussion_materials"]').value = s.discussion_materials ?? '';
  form.querySelector('[name="status"]').value = s.status;
  form.querySelector('[name="description"]').value = s.description ?? '';
  openModal('schedule-modal');
}
@endpush
