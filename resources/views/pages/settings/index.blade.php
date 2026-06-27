@extends('layouts.panel', ['page' => 'settings'])

@section('page_title', 'Pengaturan')
@section('page_subtitle', '/ Konfigurasi Aplikasi')

@section('content')
<div class="section-header">
  <div>
    <h2>Pengaturan</h2>
    <p class="text-muted">Konfigurasi aplikasi dan preferensi pengguna</p>
  </div>
</div>

<div class="settings-grid">
  <!-- Settings Menu -->
  <div class="settings-menu">
    <div class="settings-menu-item active" onclick="switchSettings(this,'set-profil')">
      <i class="fa-solid fa-user"></i> Profil Pengguna
    </div>
    <div class="settings-menu-item" onclick="switchSettings(this,'set-tampilan')">
      <i class="fa-solid fa-palette"></i> Tampilan
    </div>
    <div class="settings-menu-item" onclick="switchSettings(this,'set-notif')">
      <i class="fa-solid fa-bell"></i> Notifikasi
    </div>
    <div class="settings-menu-item" onclick="switchSettings(this,'set-keamanan')">
      <i class="fa-solid fa-shield-halved"></i> Keamanan
    </div>
    <div class="settings-menu-item" onclick="switchSettings(this,'set-sistem')">
      <i class="fa-solid fa-sliders"></i> Sistem
    </div>
  </div>

  <!-- Settings Panels -->
  <div>
    <!-- PROFIL -->
    <div class="settings-panel active" id="set-profil">
      <div class="settings-section">
        <div class="settings-section-header"><i class="fa-solid fa-user" style="margin-right:8px;color:var(--accent)"></i>Informasi Profil</div>
        <div class="settings-section-body">
          <div class="form-grid form-grid-2" style="margin-bottom:16px">
            <div class="form-group">
              <label>Nama Lengkap</label>
              <input class="form-control" type="text" value="{{ auth()->user()?->name ?? 'Admin Panel' }}">
            </div>
            <div class="form-group">
              <label>Username</label>
              <input class="form-control" type="text" value="{{ auth()->user()?->username ?? 'admin' }}">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input class="form-control" type="email" value="{{ auth()->user()?->email ?? 'admin@cinepanel.id' }}">
            </div>
            <div class="form-group">
              <label>No. Telepon</label>
              <input class="form-control" type="tel" value="+62 812-xxxx-xxxx">
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Bio</label>
              <textarea class="form-control">Admin utama sistem manajemen produksi CinePanel.</textarea>
            </div>
          </div>
          <button class="btn btn-primary" onclick="showToast('Profil berhasil disimpan!','success')">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
          </button>
        </div>
      </div>
    </div>

    <!-- TAMPILAN -->
    <div class="settings-panel" id="set-tampilan">
      <div class="settings-section">
        <div class="settings-section-header"><i class="fa-solid fa-palette" style="margin-right:8px;color:var(--accent)"></i>Tampilan & Tema</div>
        <div class="settings-section-body">
          <form method="POST" action="{{ route('settings.update') }}">
            @csrf @method('PUT')
            <div class="setting-row">
              <div class="setting-info">
                <div class="setting-label">Mode Gelap</div>
                <div class="setting-desc">Aktifkan tampilan dark mode untuk kenyamanan di ruangan gelap</div>
              </div>
              <div class="toggle on" id="darkToggle" onclick="toggleThemeFromSettings(this)"></div>
            </div>
            <div class="setting-row">
              <div class="setting-info">
                <div class="setting-label">Sidebar Collapsed</div>
                <div class="setting-desc">Tampilkan sidebar dalam mode diperkecil secara default</div>
              </div>
              <div class="toggle" onclick="this.classList.toggle('on')"></div>
            </div>
            <div class="setting-row" style="align-items:flex-start;flex-direction:column;gap:12px">
              <div class="setting-info">
                <div class="setting-label">Warna Aksen</div>
                <div class="setting-desc">Pilih warna aksen aplikasi</div>
              </div>
              <div class="theme-picker">
                <div class="theme-swatch" style="background:#8b5cf6" title="Purple" onclick="selectSwatch(this)"></div>
                <div class="theme-swatch" style="background:#3b82f6" title="Blue" onclick="selectSwatch(this)"></div>
                <div class="theme-swatch" style="background:#10b981" title="Emerald" onclick="selectSwatch(this)"></div>
                <div class="theme-swatch" style="background:#f59e0b" title="Amber" onclick="selectSwatch(this)"></div>
                <div class="theme-swatch" style="background:#ec4899" title="Pink" onclick="selectSwatch(this)"></div>
                <div class="theme-swatch" style="background:#ef4444" title="Red" onclick="selectSwatch(this)"></div>
                <div class="theme-swatch selected" style="background:#f97316" title="Orange" onclick="selectSwatch(this)"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- NOTIFIKASI -->
    <div class="settings-panel" id="set-notif">
      <div class="settings-section">
        <div class="settings-section-header"><i class="fa-solid fa-bell" style="margin-right:8px;color:var(--accent)"></i>Pengaturan Notifikasi</div>
        <div class="settings-section-body">
          <form method="POST" action="{{ route('settings.update') }}">
            @csrf @method('PUT')
            <div class="setting-row"><div class="setting-info"><div class="setting-label">Notifikasi Email</div><div class="setting-desc">Kirim ringkasan aktivitas via email</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
            <div class="setting-row"><div class="setting-info"><div class="setting-label">Notifikasi Jadwal</div><div class="setting-desc">Ingatkan 1 jam sebelum jadwal kegiatan</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
            <div class="setting-row"><div class="setting-info"><div class="setting-label">Update RAB</div><div class="setting-desc">Notifikasi saat ada perubahan anggaran</div></div><div class="toggle" onclick="this.classList.toggle('on')"></div></div>
            <div class="setting-row"><div class="setting-info"><div class="setting-label">Laporan Mingguan</div><div class="setting-desc">Kirim laporan produksi setiap Senin pagi</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
          </form>
        </div>
      </div>
    </div>

    <!-- KEAMANAN -->
    <div class="settings-panel" id="set-keamanan">
      <div class="settings-section">
        <div class="settings-section-header"><i class="fa-solid fa-shield-halved" style="margin-right:8px;color:var(--accent)"></i>Keamanan Akun</div>
        <div class="settings-section-body">
          <div class="form-grid" style="margin-bottom:16px">
            <div class="form-group">
              <label>Password Saat Ini</label>
              <input class="form-control" type="password" placeholder="••••••••">
            </div>
            <div class="form-group">
              <label>Password Baru</label>
              <input class="form-control" type="password" placeholder="Min. 8 karakter">
            </div>
            <div class="form-group">
              <label>Konfirmasi Password Baru</label>
              <input class="form-control" type="password" placeholder="Ulangi password baru">
            </div>
          </div>
          <div class="setting-row">
            <div class="setting-info"><div class="setting-label">Autentikasi 2 Faktor</div><div class="setting-desc">Tingkatkan keamanan dengan 2FA</div></div>
            <div class="toggle" onclick="this.classList.toggle('on')"></div>
          </div>
          <div style="margin-top:16px">
            <button class="btn btn-primary" onclick="showToast('Password berhasil diubah!','success')"><i class="fa-solid fa-key"></i> Ubah Password</button>
          </div>
        </div>
      </div>
    </div>

    <!-- SISTEM -->
    <div class="settings-panel" id="set-sistem">
      <div class="settings-section">
        <div class="settings-section-header"><i class="fa-solid fa-sliders" style="margin-right:8px;color:var(--accent)"></i>Pengaturan Sistem</div>
        <div class="settings-section-body">
          <form method="POST" action="{{ route('settings.update') }}">
            @csrf @method('PUT')
            <div class="form-grid form-grid-2" style="margin-bottom:16px">
              <div class="form-group">
                <label>Nama Perusahaan</label>
                <input class="form-control" type="text" name="company_name" value="{{ $settings->get('company_name')?->value ?? 'CineStudio Indonesia' }}">
              </div>
              <div class="form-group">
                <label>Zona Waktu</label>
                <select class="form-control" name="timezone">
                  <option value="WIB" {{ ($settings->get('timezone')?->value ?? 'WIB') === 'WIB' ? 'selected' : '' }}>WIB (UTC+7)</option>
                  <option value="WITA" {{ ($settings->get('timezone')?->value ?? '') === 'WITA' ? 'selected' : '' }}>WITA (UTC+8)</option>
                  <option value="WIT" {{ ($settings->get('timezone')?->value ?? '') === 'WIT' ? 'selected' : '' }}>WIT (UTC+9)</option>
                </select>
              </div>
              <div class="form-group">
                <label>Mata Uang</label>
                <select class="form-control" name="currency">
                  <option value="IDR" {{ ($settings->get('currency')?->value ?? 'IDR') === 'IDR' ? 'selected' : '' }}>IDR - Rupiah</option>
                  <option value="USD" {{ ($settings->get('currency')?->value ?? '') === 'USD' ? 'selected' : '' }}>USD - Dollar</option>
                </select>
              </div>
              <div class="form-group">
                <label>Format Tanggal</label>
                <select class="form-control" name="date_format">
                  <option value="DD/MM/YYYY" {{ ($settings->get('date_format')?->value ?? 'DD/MM/YYYY') === 'DD/MM/YYYY' ? 'selected' : '' }}>DD/MM/YYYY</option>
                  <option value="MM/DD/YYYY" {{ ($settings->get('date_format')?->value ?? '') === 'MM/DD/YYYY' ? 'selected' : '' }}>MM/DD/YYYY</option>
                  <option value="YYYY-MM-DD" {{ ($settings->get('date_format')?->value ?? '') === 'YYYY-MM-DD' ? 'selected' : '' }}>YYYY-MM-DD</option>
                </select>
              </div>
            </div>
            <div class="setting-row"><div class="setting-info"><div class="setting-label">Mode Maintenance</div><div class="setting-desc">Nonaktifkan akses untuk user lain sementara</div></div><div class="toggle" onclick="this.classList.toggle('on')"></div></div>
            <div style="margin-top:16px">
              <button class="btn btn-primary" onclick="showToast('Pengaturan sistem disimpan!','success')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
