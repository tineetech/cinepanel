@extends('layouts.panel', ['page' => 'dashboard'])

@section('page_title', 'Dashboard')
@section('page_subtitle', '/ Overview')

@section('content')
<div class="welcome-banner">
  <div class="welcome-text">
    <h2>Selamat Datang, {{ auth()->user()->name }}! 🎬</h2>
    <p>Pantau semua kegiatan produksi film Anda dari satu dasbor terpusat.</p>
  </div>
  <div class="welcome-icon"><i class="fa-solid fa-clapperboard"></i></div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon purple"><i class="fa-solid fa-clapperboard"></i></div>
    <div class="stat-data">
      <div class="val">{{ $totalFilms }}</div>
      <div class="lbl">Total Film</div>
      @if ($totalFilms > 0)
      <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> {{ $totalFilms }} terdaftar</div>
      @endif
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="fa-solid fa-masks-theater"></i></div>
    <div class="stat-data">
      <div class="val">{{ $totalCast }}</div>
      <div class="lbl">Total Pemeran</div>
      @if ($totalCast > 0)
      <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> {{ $totalCast }} terdaftar</div>
      @endif
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber"><i class="fa-solid fa-people-group"></i></div>
    <div class="stat-data">
      <div class="val">{{ $totalCrew }}</div>
      <div class="lbl">Total Crew</div>
      @if ($totalCrew > 0)
      <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> {{ $totalCrew }} terdaftar</div>
      @endif
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon pink"><i class="fa-solid fa-file-invoice-dollar"></i></div>
    <div class="stat-data">
      <div class="val">{{ $totalRab ? number_format($totalRab / 1000000, 1) : 0 }}M</div>
      <div class="lbl">Total RAB (Juta)</div>
      @if ($totalRab > 0)
      <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> Anggaran total</div>
      @endif
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fa-solid fa-map-location-dot"></i></div>
    <div class="stat-data">
      <div class="val">{{ $totalLocations }}</div>
      <div class="lbl">Lokasi Syuting</div>
      @if ($totalLocations > 0)
      <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> {{ $totalLocations }} tersedia</div>
      @endif
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon teal"><i class="fa-solid fa-calendar-check"></i></div>
    <div class="stat-data">
      <div class="val">{{ $upcomingSchedules->count() }}</div>
      <div class="lbl">Jadwal Mendatang</div>
      @if ($upcomingSchedules->count() > 0)
      <div class="chg up"><i class="fa-solid fa-circle-check"></i> {{ $upcomingSchedules->where('status', 'Selesai')->count() }} selesai</div>
      @endif
    </div>
  </div>
</div>

<div class="dash-grid">
  <div class="card">
    <div class="card-header">
      <h3><i class="fa-solid fa-clock-rotate-left text-accent" style="margin-right:8px"></i>Aktivitas Terbaru</h3>
      <a href="{{ route('activity-logs.index') }}">Lihat semua</a>
    </div>
    <div class="card-body">
      <div class="activity-list">
        @forelse ($activities as $activity)
        <div class="activity-item">
          <div class="activity-dot {{ ['purple', 'green', 'amber', 'pink'][$loop->index % 4] }}"></div>
          <div>
            <div class="activity-text">{!! $activity->description !!}</div>
            <div class="activity-time">{{ $activity->created_at->diffForHumans() }} · oleh {{ $activity->user_name }}</div>
          </div>
        </div>
        @empty
        <div class="empty-state" style="padding:30px 0">
          <i class="fa-solid fa-clock-rotate-left"></i>
          <p>Belum ada aktivitas tercatat.</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:20px;">
    <div class="card">
      <div class="card-header">
        <h3><i class="fa-solid fa-calendar-days text-accent" style="margin-right:8px"></i>Jadwal Mendatang</h3>
      </div>
      <div class="card-body">
        <div class="upcoming-list">
          @forelse ($upcomingSchedules as $schedule)
          <div class="upcoming-item">
            <div class="upcoming-date">
              <div class="day">{{ $schedule->date->format('d') }}</div>
              <div class="mon">{{ $schedule->date->locale('id')->isoFormat('MMM') }}</div>
            </div>
            <div class="upcoming-info">
              <div class="title">{{ $schedule->activity_name }}</div>
              <div class="sub"><i class="fa-solid fa-clock" style="margin-right:4px"></i>{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-' }} WIB · {{ $schedule->location ?? '-' }}</div>
            </div>
          </div>
          @empty
          <div class="empty-state" style="padding:20px 0">
            <i class="fa-solid fa-calendar-days"></i>
            <p>Tidak ada jadwal mendatang.</p>
          </div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h3><i class="fa-solid fa-chart-line text-accent" style="margin-right:8px"></i>Progress Film Aktif</h3>
      </div>
      <div class="card-body">
        <div class="progress-list">
          @forelse ($filmProgress as $fp)
          <div class="progress-item">
            <div class="prog-header">
              <span class="prog-label">{{ $fp['title'] }}</span>
              <span class="prog-pct">{{ $fp['progress'] }}%</span>
            </div>
            <div class="progress-track"><div class="progress-bar" style="width:{{ $fp['progress'] }}%"></div></div>
          </div>
          @empty
          <div class="empty-state" style="padding:20px 0">
            <i class="fa-solid fa-chart-line"></i>
            <p>Tidak ada film aktif.</p>
          </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3><i class="fa-solid fa-clapperboard text-accent" style="margin-right:8px"></i>Film Terbaru</h3>
    <button class="btn btn-primary btn-sm" onclick="window.location='{{ route('films.index') }}'"><i class="fa-solid fa-arrow-right"></i> Lihat Semua</button>
  </div>
  <div class="card-body" style="padding:0">
    <table>
      <thead>
        <tr>
          <th>Film</th><th>Genre</th><th>Sutradara</th><th>Status</th><th>Progress</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($recentFilms as $film)
        <tr>
          <td>
            <div class="film-cell">
              <div class="film-poster">@if ($film->image) <img src="{{ Storage::url($film->image) }}" alt="{{ $film->title }}" style="width:100%;height:100%;object-fit:cover;border-radius:6px"> @else <i class="fa-solid fa-film"></i> @endif</div>
              <div class="film-info">
                <div class="name">{{ $film->title }}</div>
                <div class="sub">{{ $film->year }} · {{ $film->genre }}</div>
              </div>
            </div>
          </td>
          <td><span class="badge badge-purple">{{ $film->genre }}</span></td>
          <td>{{ $film->director }}</td>
          <td>
            @php
              $statusClass = match($film->status) {
                'Produksi' => 'badge-green',
                'Pra-Produksi' => 'badge-amber',
                'Pasca-Produksi' => 'badge-purple',
                default => 'badge-gray',
              };
            @endphp
            <span class="badge {{ $statusClass }}">{{ $film->status }}</span>
          </td>
          <td>
            @php
              $progress = $filmProgress->firstWhere('id', $film->id);
            @endphp
            <div class="progress-track" style="width:100px">
              <div class="progress-bar" style="width:{{ $progress['progress'] ?? 0 }}%"></div>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5">
            <div class="empty-state" style="padding:30px 0">
              <i class="fa-solid fa-film"></i>
              <p>Belum ada film yang terdaftar.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
