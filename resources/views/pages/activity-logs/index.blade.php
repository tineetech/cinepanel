@extends('layouts.panel', ['page' => 'activity-logs'])

@section('page_title', 'Log Aktivitas')
@section('page_subtitle', '/ Riwayat Aktivitas Sistem')

@section('content')
<div class="section-header">
  <div>
    <h2>Log Aktivitas</h2>
    <p class="text-muted">Riwayat semua aktivitas dalam sistem</p>
  </div>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari aktivitas..." oninput="filterTable(this,'activity-log-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'activity-log-table')">
    <option value="">Semua Tipe</option>
    <option>film</option>
    <option>cast-member</option>
    <option>crew</option>
    <option>property</option>
    <option>rab-item</option>
    <option>location</option>
    <option>schedule</option>
    <option>script</option>
    <option>shot-list</option>
  </select>
</div>

<div class="table-wrap">
  <table id="activity-log-table">
    <thead>
      <tr>
        <th>#</th><th>Deskripsi</th><th>User</th><th>Tipe</th><th>Waktu</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($activityLogs as $log)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>{{ $log->description }}</td>
        <td>{{ $log->user_name }}</td>
        <td>
          @php
            $sc = match($log->type) {
              'film' => 'badge-purple',
              'cast-member' => 'badge-green',
              'crew' => 'badge-blue',
              'property' => 'badge-amber',
              'rab-item' => 'badge-red',
              'location' => 'badge-green',
              'schedule' => 'badge-blue',
              'script' => 'badge-purple',
              'shot-list' => 'badge-amber',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $log->type }}</span>
        </td>
        <td class="text-muted">{{ $log->created_at->diffForHumans() }}</td>
        <td>
          <form method="POST" action="{{ route('activity-logs.destroy', $log) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus log ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm btn-icon" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6">
          <div class="empty-state" style="padding:40px 20px">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <h3>Belum Ada Aktivitas</h3>
            <p>Log aktivitas akan muncul saat ada perubahan data.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($activityLogs->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $activityLogs->firstItem() }}–{{ $activityLogs->lastItem() }} dari {{ $activityLogs->total() }} log</span>
    <div class="pagination-btns">
      @if ($activityLogs->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $activityLogs->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($activityLogs->getUrlRange(1, $activityLogs->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $activityLogs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($activityLogs->hasMorePages())
        <a href="{{ $activityLogs->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>
@endsection
