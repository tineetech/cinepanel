@extends('layouts.panel', ['page' => 'notifications'])

@section('page_title', 'Notifikasi')
@section('page_subtitle', '/ Pusat Notifikasi')

@section('content')
<div class="section-header">
  <div>
    <h2>Notifikasi</h2>
    <p class="text-muted">Pusat notifikasi dan pemberitahuan sistem</p>
  </div>
</div>

<div class="toolbar">
  <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Cari notifikasi..." oninput="filterTable(this,'notification-table')">
  </div>
  <select class="filter-select" onchange="filterByStatus(this,'notification-table')">
    <option value="">Semua Status</option>
    <option>info</option>
    <option>success</option>
    <option>warning</option>
    <option>error</option>
  </select>
</div>

<div class="table-wrap">
  <table id="notification-table">
    <thead>
      <tr>
        <th>#</th><th>Judul</th><th>Pesan</th><th>Tipe</th><th>Status</th><th>Waktu</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($notifications as $notification)
      <tr>
        <td class="text-muted">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td>{{ $notification->title }}</td>
        <td>{{ $notification->message ?? '-' }}</td>
        <td>
          @php
            $sc = match($notification->type) {
              'info' => 'badge-blue',
              'success' => 'badge-green',
              'warning' => 'badge-amber',
              'error' => 'badge-red',
              default => 'badge-gray',
            };
          @endphp
          <span class="badge {{ $sc }}">{{ $notification->type ?? '-' }}</span>
        </td>
        <td>
          @if ($notification->is_read)
            <span class="badge badge-gray">Sudah Dibaca</span>
          @else
            <span class="badge badge-amber">Belum Dibaca</span>
          @endif
        </td>
        <td class="text-muted">{{ $notification->created_at->diffForHumans() }}</td>
        <td>
          <div class="action-btns">
            @if (!$notification->is_read)
              <form method="POST" action="{{ route('notifications.update', $notification) }}" style="display:inline">
                @csrf @method('PUT')
                <button class="btn btn-ghost btn-sm btn-icon" data-tip="Tandai Dibaca"><i class="fa-solid fa-check"></i></button>
              </form>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $notification) }}" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm btn-icon" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7">
          <div class="empty-state" style="padding:40px 20px">
            <i class="fa-solid fa-bell-slash"></i>
            <h3>Tidak Ada Notifikasi</h3>
            <p>Belum ada notifikasi untuk ditampilkan.</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  @if ($notifications->hasPages())
  <div class="table-pagination">
    <span class="pagination-info">Menampilkan {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} dari {{ $notifications->total() }} notifikasi</span>
    <div class="pagination-btns">
      @if ($notifications->onFirstPage())
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      @else
        <a href="{{ $notifications->previousPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pg-btn {{ $page === $notifications->currentPage() ? 'active' : '' }}">{{ $page }}</a>
      @endforeach
      @if ($notifications->hasMorePages())
        <a href="{{ $notifications->nextPageUrl() }}" class="pg-btn"><i class="fa-solid fa-chevron-right"></i></a>
      @else
        <button class="pg-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
      @endif
    </div>
  </div>
  @endif
</div>
@endsection
