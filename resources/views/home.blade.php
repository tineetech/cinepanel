<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>CinePanel — Manajemen Produksi Film</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ============================================================
   DESIGN TOKENS
   ============================================================ */
:root {
  --purple-50:  #f5f3ff;
  --purple-100: #ede9fe;
  --purple-200: #ddd6fe;
  --purple-400: #a78bfa;
  --purple-500: #8b5cf6;
  --purple-600: #7c3aed;
  --purple-700: #6d28d9;
  --purple-800: #5b21b6;
  --purple-900: #4c1d95;

  --accent: #f97316;
  --accent-hover: #ea580c;
  --accent-soft: rgba(249,115,22,.15);
  --accent-glow: rgba(249,115,22,.35);

  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 18px;
  --radius-xl: 24px;

  --font: 'Poppins', sans-serif;
  --sidebar-w: 260px;
  --topbar-h: 64px;
  --transition: .22s cubic-bezier(.4,0,.2,1);
}

/* ---- DARK THEME (default) ---- */
[data-theme="dark"] {
  --bg:        #0d0d14;
  --bg-2:      #13131f;
  --bg-3:      #1a1a2e;
  --bg-4:      #21213a;
  --border:    rgba(255,255,255,.07);
  --border-2:  rgba(255,255,255,.12);
  --text-1:    #f0efff;
  --text-2:    #9b99c0;
  --text-3:    #5e5c7a;
  --shadow:    0 4px 24px rgba(0,0,0,.55);
  --shadow-sm: 0 2px 10px rgba(0,0,0,.35);
  --badge-bg:  rgba(255,255,255,.06);
}

/* ---- LIGHT THEME ---- */
[data-theme="light"] {
  --bg:        #f4f2ff;
  --bg-2:      #ffffff;
  --bg-3:      #ece9ff;
  --bg-4:      #ddd8ff;
  --border:    rgba(0,0,0,.07);
  --border-2:  rgba(0,0,0,.13);
  --text-1:    #1a1740;
  --text-2:    #5b567a;
  --text-3:    #9b96bc;
  --shadow:    0 4px 24px rgba(100,80,200,.12);
  --shadow-sm: 0 2px 10px rgba(100,80,200,.08);
  --badge-bg:  rgba(0,0,0,.05);
}

/* ============================================================
   RESET & BASE
   ============================================================ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: var(--font);
  background: var(--bg);
  color: var(--text-1);
  min-height: 100vh;
  display: flex;
  overflow-x: hidden;
  transition: background var(--transition), color var(--transition);
}
a { text-decoration: none; color: inherit; }
button { font-family: var(--font); cursor: pointer; border: none; background: none; }
input, select, textarea { font-family: var(--font); }
ul { list-style: none; }

/* ============================================================
   SCROLLBAR
   ============================================================ */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--bg-4); border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: var(--accent); }

/* ============================================================
   SIDEBAR
   ============================================================ */
.sidebar {
  width: var(--sidebar-w);
  height: 100vh;
  background: var(--bg-2);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 0; left: 0;
  z-index: 200;
  transition: width var(--transition), transform var(--transition), background var(--transition);
}
.sidebar.collapsed { width: 68px; }

.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 18px;
  border-bottom: 1px solid var(--border);
  min-height: var(--topbar-h);
}
.logo-icon {
  width: 36px; height: 36px;
  background: linear-gradient(135deg, var(--accent), #fb923c);
  border-radius: 10px;
  display: grid; place-items: center;
  font-size: 16px; color: #fff;
  flex-shrink: 0;
  box-shadow: 0 0 18px var(--accent-glow);
}
.logo-text {
  font-size: 17px; font-weight: 700;
  color: var(--text-1);
  white-space: nowrap;
  overflow: hidden;
  transition: opacity var(--transition), width var(--transition);
}
.logo-text span { color: var(--accent); }
.sidebar.collapsed .logo-text { opacity: 0; width: 0; }

.sidebar-toggle {
  margin-left: auto;
  width: 28px; height: 28px;
  border-radius: 6px;
  background: var(--badge-bg);
  color: var(--text-2);
  display: grid; place-items: center;
  font-size: 12px;
  transition: background var(--transition), color var(--transition);
  flex-shrink: 0;
}
.sidebar-toggle:hover { background: var(--accent-soft); color: var(--accent); }
.sidebar.collapsed .sidebar-toggle { margin-left: 0; }

/* NAV */
.sidebar-nav {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 12px 0;
}
.nav-group { margin-bottom: 4px; }
.nav-group-label {
  font-size: 10px;
  font-weight: 600;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--text-3);
  padding: 10px 20px 4px;
  white-space: nowrap;
  overflow: hidden;
  transition: opacity var(--transition);
}
.sidebar.collapsed .nav-group-label { opacity: 0; }
.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 18px;
  border-radius: var(--radius-sm);
  margin: 2px 8px;
  cursor: pointer;
  transition: background var(--transition), color var(--transition);
  position: relative;
  white-space: nowrap;
  color: var(--text-2);
  font-size: 13.5px;
  font-weight: 500;
}
.nav-item:hover { background: var(--accent-soft); color: var(--accent); }
.nav-item.active {
  background: var(--accent-soft);
  color: var(--accent);
}
.nav-item.active::before {
  content: '';
  position: absolute;
  left: -8px; top: 50%; transform: translateY(-50%);
  width: 3px; height: 20px;
  background: var(--accent);
  border-radius: 0 3px 3px 0;
}
.nav-item i:first-child {
  width: 20px; text-align: center;
  font-size: 15px;
  flex-shrink: 0;
}
.nav-label { overflow: hidden; transition: opacity var(--transition), width var(--transition); }
.sidebar.collapsed .nav-label { opacity: 0; width: 0; pointer-events: none; }
.nav-badge {
  margin-left: auto;
  background: var(--accent);
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  padding: 1px 7px;
  border-radius: 20px;
  flex-shrink: 0;
}
.sidebar.collapsed .nav-badge { display: none; }

.sidebar-footer {
  border-top: 1px solid var(--border);
  padding: 14px 18px;
  display: flex; align-items: center; gap: 10px;
}
.avatar {
  width: 34px; height: 34px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--accent), #fb923c);
  display: grid; place-items: center;
  font-size: 13px; color: #fff; font-weight: 700;
  flex-shrink: 0;
}
.user-info { overflow: hidden; }
.user-name { font-size: 13px; font-weight: 600; white-space: nowrap; }
.user-role { font-size: 11px; color: var(--text-3); white-space: nowrap; }
.sidebar.collapsed .user-info { display: none; }

/* ============================================================
   MAIN LAYOUT
   ============================================================ */
.main-wrap {
  margin-left: var(--sidebar-w);
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  transition: margin-left var(--transition);
}
.main-wrap.expanded { margin-left: 68px; }

/* TOPBAR */
.topbar {
  height: var(--topbar-h);
  background: var(--bg-2);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center;
  padding: 0 24px;
  gap: 16px;
  position: sticky; top: 0; z-index: 100;
  transition: background var(--transition);
}
.topbar-title { font-size: 16px; font-weight: 700; flex: 1; }
.topbar-title small { font-size: 12px; color: var(--text-3); font-weight: 400; margin-left: 6px; }

.topbar-actions { display: flex; align-items: center; gap: 10px; }
.icon-btn {
  width: 36px; height: 36px;
  border-radius: var(--radius-sm);
  background: var(--badge-bg);
  color: var(--text-2);
  display: grid; place-items: center;
  font-size: 14px;
  transition: background var(--transition), color var(--transition);
  position: relative;
}
.icon-btn:hover { background: var(--accent-soft); color: var(--accent); }
.icon-btn .notif-dot {
  position: absolute;
  top: 6px; right: 6px;
  width: 7px; height: 7px;
  background: var(--accent);
  border-radius: 50%;
  border: 2px solid var(--bg-2);
}

/* ============================================================
   PAGE CONTENT
   ============================================================ */
.page-content { padding: 28px 28px 40px; flex: 1; }
.page { display: none; }
.page.active { display: block; }

/* ============================================================
   DASHBOARD
   ============================================================ */
.welcome-banner {
  background: linear-gradient(135deg, #c2410c 0%, var(--accent) 60%, #fb923c 100%);
  border-radius: var(--radius-lg);
  padding: 28px 32px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 8px 32px var(--accent-glow);
}
.welcome-banner::before {
  content: '';
  position: absolute;
  top: -40px; right: 80px;
  width: 200px; height: 200px;
  border-radius: 50%;
  background: rgba(255,255,255,.06);
}
.welcome-banner::after {
  content: '';
  position: absolute;
  bottom: -60px; right: 20px;
  width: 160px; height: 160px;
  border-radius: 50%;
  background: rgba(255,255,255,.04);
}
.welcome-text h2 { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 6px; }
.welcome-text p { font-size: 13px; color: rgba(255,255,255,.75); }
.welcome-icon { font-size: 64px; color: rgba(255,255,255,.2); position: relative; z-index: 1; }

/* STAT CARDS */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 18px;
  margin-bottom: 28px;
}
.stat-card {
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  transition: border-color var(--transition), transform .2s;
  box-shadow: var(--shadow-sm);
}
.stat-card:hover { border-color: var(--accent); transform: translateY(-2px); }
.stat-icon {
  width: 48px; height: 48px;
  border-radius: var(--radius-sm);
  display: grid; place-items: center;
  font-size: 20px;
  flex-shrink: 0;
}
.stat-icon.purple { background: var(--accent-soft); color: var(--accent); }
.stat-icon.green  { background: rgba(16,185,129,.15); color: #10b981; }
.stat-icon.amber  { background: rgba(245,158,11,.15);  color: #f59e0b; }
.stat-icon.pink   { background: rgba(236,72,153,.15);  color: #ec4899; }
.stat-icon.blue   { background: rgba(59,130,246,.15);  color: #3b82f6; }
.stat-icon.teal   { background: rgba(20,184,166,.15);  color: #14b8a6; }
.stat-data .val { font-size: 26px; font-weight: 800; line-height: 1; }
.stat-data .lbl { font-size: 12px; color: var(--text-2); margin-top: 3px; }
.stat-data .chg { font-size: 11px; margin-top: 4px; }
.chg.up { color: #10b981; }
.chg.down { color: #ef4444; }

/* GRID 2 COL */
.dash-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px; }
@media (max-width: 1024px) { .dash-grid { grid-template-columns: 1fr; } }

.card {
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: background var(--transition);
}
.card-header {
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
.card-header h3 { font-size: 14px; font-weight: 700; }
.card-header a, .card-header button.link {
  font-size: 12px;
  color: var(--accent);
  font-weight: 500;
  background: none; border: none; cursor: pointer;
}
.card-body { padding: 16px 20px; }

/* ACTIVITY FEED */
.activity-list { display: flex; flex-direction: column; gap: 14px; }
.activity-item { display: flex; gap: 12px; align-items: flex-start; }
.activity-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  margin-top: 5px;
  flex-shrink: 0;
}
.activity-dot.purple { background: var(--accent); }
.activity-dot.green  { background: #10b981; }
.activity-dot.amber  { background: #f59e0b; }
.activity-dot.pink   { background: #ec4899; }
.activity-text { font-size: 13px; line-height: 1.5; }
.activity-text strong { font-weight: 600; }
.activity-time { font-size: 11px; color: var(--text-3); margin-top: 2px; }

/* UPCOMING */
.upcoming-list { display: flex; flex-direction: column; gap: 12px; }
.upcoming-item {
  display: flex; gap: 12px; align-items: center;
  padding: 10px 12px;
  border-radius: var(--radius-sm);
  background: var(--bg-3);
  border: 1px solid var(--border);
}
.upcoming-date {
  text-align: center;
  min-width: 40px;
}
.upcoming-date .day { font-size: 20px; font-weight: 800; color: var(--accent); line-height: 1; }
.upcoming-date .mon { font-size: 10px; color: var(--text-3); text-transform: uppercase; }
.upcoming-info .title { font-size: 13px; font-weight: 600; }
.upcoming-info .sub  { font-size: 11px; color: var(--text-2); margin-top: 1px; }

/* PROGRESS */
.progress-list { display: flex; flex-direction: column; gap: 14px; }
.progress-item .prog-header { display: flex; justify-content: space-between; margin-bottom: 6px; }
.progress-item .prog-label { font-size: 13px; font-weight: 500; }
.progress-item .prog-pct { font-size: 12px; color: var(--text-2); }
.progress-track {
  height: 6px;
  background: var(--bg-4);
  border-radius: 99px;
  overflow: hidden;
}
.progress-bar {
  height: 100%;
  border-radius: 99px;
  background: linear-gradient(90deg, var(--accent), #fb923c);
  transition: width .8s cubic-bezier(.4,0,.2,1);
}

/* ============================================================
   TABLE & PAGE HEADER
   ============================================================ */
.section-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
}
.section-header h2 { font-size: 18px; font-weight: 700; }
.section-header p  { font-size: 13px; color: var(--text-2); margin-top: 2px; }

.toolbar {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 16px; flex-wrap: wrap;
}
.search-box {
  display: flex; align-items: center; gap: 8px;
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 8px 14px;
  flex: 1; min-width: 200px;
  transition: border-color var(--transition);
}
.search-box:focus-within { border-color: var(--accent); }
.search-box i { color: var(--text-3); font-size: 13px; }
.search-box input {
  border: none; background: none;
  color: var(--text-1); font-size: 13px;
  outline: none; width: 100%;
}
.search-box input::placeholder { color: var(--text-3); }

.filter-select {
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 8px 14px;
  color: var(--text-1); font-size: 13px;
  outline: none; cursor: pointer;
  transition: border-color var(--transition);
}
.filter-select:focus { border-color: var(--accent); }

.table-wrap {
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
}
table { width: 100%; border-collapse: collapse; }
thead th {
  padding: 12px 16px;
  text-align: left;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .06em;
  text-transform: uppercase;
  color: var(--text-3);
  background: var(--bg-3);
  border-bottom: 1px solid var(--border);
}
tbody tr {
  border-bottom: 1px solid var(--border);
  transition: background var(--transition);
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--bg-3); }
tbody td {
  padding: 12px 16px;
  font-size: 13px;
  color: var(--text-1);
  vertical-align: middle;
}

.table-pagination {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 20px;
  border-top: 1px solid var(--border);
  background: var(--bg-2);
}
.pagination-info { font-size: 12px; color: var(--text-2); }
.pagination-btns { display: flex; gap: 6px; }
.pg-btn {
  width: 30px; height: 30px;
  border-radius: 6px;
  background: var(--bg-3);
  color: var(--text-2);
  font-size: 12px;
  display: grid; place-items: center;
  border: 1px solid var(--border);
  transition: all var(--transition);
}
.pg-btn:hover, .pg-btn.active {
  background: var(--accent);
  color: #fff; border-color: var(--accent);
}

/* ============================================================
   BADGES / PILLS
   ============================================================ */
.badge {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 10px;
  border-radius: 99px;
  font-size: 11px;
  font-weight: 600;
}
.badge-purple { background: var(--accent-soft); color: var(--accent); }
.badge-green  { background: rgba(16,185,129,.15); color: #10b981; }
.badge-amber  { background: rgba(245,158,11,.15);  color: #f59e0b; }
.badge-red    { background: rgba(239,68,68,.15);   color: #ef4444; }
.badge-blue   { background: rgba(59,130,246,.15);  color: #3b82f6; }
.badge-gray   { background: var(--badge-bg); color: var(--text-2); }

/* FILM POSTER CELL */
.film-cell { display: flex; align-items: center; gap: 10px; }
.film-poster {
  width: 38px; height: 50px;
  border-radius: 6px;
  object-fit: cover;
  background: var(--bg-4);
  display: grid; place-items: center;
  color: var(--text-3);
  font-size: 16px;
  flex-shrink: 0;
  border: 1px solid var(--border);
}
.film-info .name { font-size: 13px; font-weight: 600; }
.film-info .sub  { font-size: 11px; color: var(--text-2); margin-top: 1px; }

.avatar-cell { display: flex; align-items: center; gap: 8px; }
.avatar-sm {
  width: 30px; height: 30px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--accent), #fb923c);
  display: grid; place-items: center;
  font-size: 11px; font-weight: 700; color: #fff;
  flex-shrink: 0;
}

/* ============================================================
   ACTION BUTTONS
   ============================================================ */
.btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 18px;
  border-radius: var(--radius-sm);
  font-size: 13px; font-weight: 600;
  transition: all var(--transition);
  cursor: pointer; border: none;
}
.btn-primary {
  background: var(--accent);
  color: #fff;
  box-shadow: 0 4px 14px var(--accent-glow);
}
.btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); }
.btn-outline {
  background: none;
  border: 1px solid var(--border-2);
  color: var(--text-2);
}
.btn-outline:hover { border-color: var(--accent); color: var(--accent); }
.btn-ghost {
  background: var(--badge-bg);
  color: var(--text-2);
}
.btn-ghost:hover { background: var(--accent-soft); color: var(--accent); }
.btn-danger {
  background: rgba(239,68,68,.15);
  color: #ef4444;
}
.btn-danger:hover { background: rgba(239,68,68,.25); }
.btn-sm { padding: 5px 12px; font-size: 12px; }
.btn-icon { width: 30px; height: 30px; padding: 0; display: grid; place-items: center; }

.action-btns { display: flex; gap: 6px; }

/* ============================================================
   MODAL / POPUP
   ============================================================ */
.modal-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,.65);
  backdrop-filter: blur(4px);
  z-index: 500;
  display: none;
  align-items: center; justify-content: center;
  padding: 20px;
}
.modal-overlay.open { display: flex; }
.modal {
  background: var(--bg-2);
  border: 1px solid var(--border-2);
  border-radius: var(--radius-lg);
  width: 100%; max-width: 560px;
  max-height: 90vh;
  display: flex; flex-direction: column;
  box-shadow: var(--shadow);
  animation: modalIn .25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes modalIn {
  from { opacity: 0; transform: scale(.92) translateY(12px); }
  to   { opacity: 1; transform: scale(1)   translateY(0); }
}
.modal-header {
  padding: 18px 24px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
.modal-header h3 { font-size: 16px; font-weight: 700; }
.modal-header .modal-icon {
  width: 36px; height: 36px;
  border-radius: 10px;
  background: var(--accent-soft);
  color: var(--accent);
  display: grid; place-items: center;
  font-size: 15px;
  margin-right: 10px;
}
.modal-header-left { display: flex; align-items: center; }
.modal-close {
  width: 30px; height: 30px;
  border-radius: 6px;
  background: var(--badge-bg);
  color: var(--text-2);
  display: grid; place-items: center;
  font-size: 13px;
  transition: all var(--transition);
}
.modal-close:hover { background: rgba(239,68,68,.15); color: #ef4444; }
.modal-body {
  padding: 20px 24px;
  overflow-y: auto;
  flex: 1;
}
.modal-footer {
  padding: 14px 24px;
  border-top: 1px solid var(--border);
  display: flex; justify-content: flex-end; gap: 10px;
}

/* FORM */
.form-grid { display: grid; gap: 16px; }
.form-grid-2 { grid-template-columns: 1fr 1fr; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 12px; font-weight: 600; color: var(--text-2); }
.form-control {
  background: var(--bg-3);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 9px 13px;
  color: var(--text-1);
  font-size: 13px;
  outline: none;
  transition: border-color var(--transition);
  width: 100%;
}
.form-control:focus { border-color: var(--accent); }
.form-control::placeholder { color: var(--text-3); }
textarea.form-control { resize: vertical; min-height: 80px; }
select.form-control { cursor: pointer; }

.form-divider {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: var(--text-3);
  padding: 4px 0;
  border-bottom: 1px solid var(--border);
  margin-bottom: 4px;
}

/* ============================================================
   SETTINGS
   ============================================================ */
.settings-grid {
  display: grid;
  grid-template-columns: 240px 1fr;
  gap: 24px;
  align-items: start;
}
@media (max-width: 800px) { .settings-grid { grid-template-columns: 1fr; } }
.settings-menu {
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  overflow: hidden;
}
.settings-menu-item {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 16px;
  font-size: 13px; font-weight: 500;
  cursor: pointer;
  border-bottom: 1px solid var(--border);
  color: var(--text-2);
  transition: all var(--transition);
}
.settings-menu-item:last-child { border-bottom: none; }
.settings-menu-item:hover { background: var(--bg-3); color: var(--accent); }
.settings-menu-item.active { background: var(--accent-soft); color: var(--accent); font-weight: 600; }
.settings-menu-item i { width: 18px; text-align: center; font-size: 13px; }

.settings-panel { display: none; }
.settings-panel.active { display: block; }
.settings-section {
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  overflow: hidden;
  margin-bottom: 20px;
}
.settings-section-header {
  padding: 14px 20px;
  border-bottom: 1px solid var(--border);
  font-size: 14px; font-weight: 700;
  background: var(--bg-3);
}
.settings-section-body { padding: 20px; }
.setting-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid var(--border);
}
.setting-row:last-child { border-bottom: none; }
.setting-info .setting-label { font-size: 13px; font-weight: 600; }
.setting-info .setting-desc  { font-size: 12px; color: var(--text-2); margin-top: 2px; }

/* TOGGLE SWITCH */
.toggle {
  width: 42px; height: 22px;
  background: var(--bg-4);
  border-radius: 99px;
  position: relative; cursor: pointer;
  transition: background var(--transition);
  flex-shrink: 0;
}
.toggle.on { background: var(--accent); }
.toggle::after {
  content: '';
  position: absolute;
  left: 3px; top: 3px;
  width: 16px; height: 16px;
  background: #fff;
  border-radius: 50%;
  transition: left var(--transition);
  box-shadow: 0 1px 4px rgba(0,0,0,.3);
}
.toggle.on::after { left: 23px; }

/* COLOR THEME PICKER */
.theme-picker { display: flex; gap: 10px; flex-wrap: wrap; }
.theme-swatch {
  width: 36px; height: 36px;
  border-radius: 50%;
  cursor: pointer;
  transition: transform .2s, box-shadow .2s;
  border: 3px solid transparent;
}
.theme-swatch:hover { transform: scale(1.1); }
.theme-swatch.selected { border-color: var(--text-1); box-shadow: 0 0 0 3px var(--bg-2); }

/* ============================================================
   EMPTY STATE
   ============================================================ */
.empty-state {
  text-align: center; padding: 60px 20px;
  color: var(--text-3);
}
.empty-state i { font-size: 48px; margin-bottom: 16px; }
.empty-state h3 { font-size: 15px; font-weight: 600; color: var(--text-2); margin-bottom: 6px; }
.empty-state p  { font-size: 13px; margin-bottom: 16px; }

/* ============================================================
   TOAST
   ============================================================ */
.toast-container {
  position: fixed; bottom: 24px; right: 24px;
  z-index: 9000;
  display: flex; flex-direction: column; gap: 8px;
}
.toast {
  display: flex; align-items: center; gap: 10px;
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-left: 4px solid var(--accent);
  border-radius: var(--radius-sm);
  padding: 12px 16px;
  min-width: 260px;
  box-shadow: var(--shadow);
  font-size: 13px;
  font-weight: 500;
  animation: slideIn .3s cubic-bezier(.34,1.56,.64,1);
}
@keyframes slideIn { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: none; } }
.toast.success { border-left-color: #10b981; }
.toast.error   { border-left-color: #ef4444; }
.toast i { font-size: 15px; }
.toast.success i { color: #10b981; }
.toast.error   i { color: #ef4444; }
.toast.info    i { color: var(--accent); }

/* ============================================================
   MISC UTILS
   ============================================================ */
.text-muted  { color: var(--text-2); }
.text-accent { color: var(--accent); }
.fw-600 { font-weight: 600; }
.fw-700 { font-weight: 700; }
.gap-8  { gap: 8px; }
.mt-4   { margin-top: 4px; }
.flex   { display: flex; }
.items-center { align-items: center; }

/* ---- TOOLTIP ---- */
[data-tip] { position: relative; }
[data-tip]:hover::after {
  content: attr(data-tip);
  position: absolute;
  left: 50%; top: calc(100% + 6px);
  transform: translateX(-50%);
  background: var(--bg-4);
  color: var(--text-1);
  font-size: 11px;
  padding: 4px 8px;
  border-radius: 5px;
  white-space: nowrap;
  pointer-events: none;
  z-index: 99;
}

/* ---- RESPONSIVE ---- */
@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .sidebar.mobile-open { transform: none; width: var(--sidebar-w); }
  .main-wrap { margin-left: 0 !important; }
  .topbar { padding: 0 16px; }
  .page-content { padding: 16px; }
  .form-grid-2 { grid-template-columns: 1fr; }
  .welcome-icon { display: none; }
  .dash-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- ====================================================
     SIDEBAR
     ==================================================== -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="fa-solid fa-film"></i></div>
    <span class="logo-text">Cine<span>Panel</span></span>
    <button class="sidebar-toggle" onclick="toggleSidebar()" title="Collapse">
      <i class="fa-solid fa-angles-left" id="toggleIcon"></i>
    </button>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-group">
      <div class="nav-group-label">Utama</div>
      <div class="nav-item active" onclick="navigate('dashboard')">
        <i class="fa-solid fa-chart-pie"></i>
        <span class="nav-label">Dashboard</span>
        <span class="nav-badge">!</span>
      </div>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Manajemen Film</div>
      <div class="nav-item" onclick="navigate('film')">
        <i class="fa-solid fa-clapperboard"></i>
        <span class="nav-label">Kelola Film</span>
      </div>
      <div class="nav-item" onclick="navigate('pemeran')">
        <i class="fa-solid fa-masks-theater"></i>
        <span class="nav-label">Kelola Pemeran</span>
      </div>
      <div class="nav-item" onclick="navigate('crew')">
        <i class="fa-solid fa-people-group"></i>
        <span class="nav-label">Kelola Crew</span>
      </div>
      <div class="nav-item" onclick="navigate('properti')">
        <i class="fa-solid fa-boxes-stacked"></i>
        <span class="nav-label">Kebutuhan Properti</span>
      </div>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Perencanaan</div>
      <div class="nav-item" onclick="navigate('rab')">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <span class="nav-label">Kelola RAB</span>
      </div>
      <div class="nav-item" onclick="navigate('lokasi')">
        <i class="fa-solid fa-map-location-dot"></i>
        <span class="nav-label">Kelola Lokasi</span>
      </div>
      <div class="nav-item" onclick="navigate('jadwal')">
        <i class="fa-solid fa-calendar-days"></i>
        <span class="nav-label">Jadwal Produksi</span>
      </div>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Kreatif</div>
      <div class="nav-item" onclick="navigate('skenario')">
        <i class="fa-solid fa-scroll"></i>
        <span class="nav-label">Kelola Skenario</span>
      </div>
      <div class="nav-item" onclick="navigate('shotlist')">
        <i class="fa-solid fa-list-check"></i>
        <span class="nav-label">Kelola Shot List</span>
      </div>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Sistem</div>
      <div class="nav-item" onclick="navigate('pengaturan')">
        <i class="fa-solid fa-gear"></i>
        <span class="nav-label">Pengaturan</span>
      </div>
    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="avatar">AP</div>
    <div class="user-info">
      <div class="user-name">Admin Panel</div>
      <div class="user-role">Super Admin</div>
    </div>
  </div>
</aside>

<!-- ====================================================
     MAIN
     ==================================================== -->
<div class="main-wrap" id="mainWrap">

  <!-- TOPBAR -->
  <header class="topbar">
    <button class="icon-btn" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div class="topbar-title" id="topbarTitle">
      Dashboard <small>/ Overview</small>
    </div>
    <div class="topbar-actions">
      <button class="icon-btn" onclick="toggleTheme()" id="themeBtn" data-tip="Toggle Mode">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
      </button>
      <button class="icon-btn" data-tip="Notifikasi">
        <i class="fa-solid fa-bell"></i>
        <span class="notif-dot"></span>
      </button>
      <button class="icon-btn" data-tip="Bantuan">
        <i class="fa-solid fa-circle-question"></i>
      </button>
      <div class="avatar" style="cursor:pointer" data-tip="Profil">AP</div>
    </div>
  </header>

  <!-- PAGE CONTENT -->
  <main class="page-content">

    <!-- =================== DASHBOARD =================== -->
    <div class="page active" id="page-dashboard">

      <div class="welcome-banner">
        <div class="welcome-text">
          <h2>Selamat Datang, Admin! 🎬</h2>
          <p>Pantau semua kegiatan produksi film Anda dari satu dasbor terpusat.</p>
        </div>
        <div class="welcome-icon"><i class="fa-solid fa-clapperboard"></i></div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-clapperboard"></i></div>
          <div class="stat-data">
            <div class="val">12</div>
            <div class="lbl">Total Film</div>
            <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> +2 bulan ini</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-masks-theater"></i></div>
          <div class="stat-data">
            <div class="val">48</div>
            <div class="lbl">Total Pemeran</div>
            <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> +6 baru</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon amber"><i class="fa-solid fa-people-group"></i></div>
          <div class="stat-data">
            <div class="val">35</div>
            <div class="lbl">Total Crew</div>
            <div class="chg down"><i class="fa-solid fa-arrow-trend-down"></i> -2 resign</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon pink"><i class="fa-solid fa-file-invoice-dollar"></i></div>
          <div class="stat-data">
            <div class="val">2.4M</div>
            <div class="lbl">Total RAB (Juta)</div>
            <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> On budget</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-map-location-dot"></i></div>
          <div class="stat-data">
            <div class="val">18</div>
            <div class="lbl">Lokasi Syuting</div>
            <div class="chg up"><i class="fa-solid fa-arrow-trend-up"></i> +3 baru</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon teal"><i class="fa-solid fa-calendar-check"></i></div>
          <div class="stat-data">
            <div class="val">7</div>
            <div class="lbl">Jadwal Minggu Ini</div>
            <div class="chg up"><i class="fa-solid fa-circle-check"></i> 4 selesai</div>
          </div>
        </div>
      </div>

      <div class="dash-grid">
        <!-- Aktivitas Terbaru -->
        <div class="card">
          <div class="card-header">
            <h3><i class="fa-solid fa-clock-rotate-left text-accent" style="margin-right:8px"></i>Aktivitas Terbaru</h3>
            <a href="#" onclick="return false">Lihat semua</a>
          </div>
          <div class="card-body">
            <div class="activity-list">
              <div class="activity-item">
                <div class="activity-dot purple"></div>
                <div>
                  <div class="activity-text"><strong>Film "Fajar di Selatan"</strong> ditambahkan ke daftar proyek</div>
                  <div class="activity-time">2 menit lalu · oleh Budi Santoso</div>
                </div>
              </div>
              <div class="activity-item">
                <div class="activity-dot green"></div>
                <div>
                  <div class="activity-text"><strong>Andika Pratama</strong> berhasil ditambahkan sebagai pemeran utama</div>
                  <div class="activity-time">28 menit lalu · oleh Rina Dewi</div>
                </div>
              </div>
              <div class="activity-item">
                <div class="activity-dot amber"></div>
                <div>
                  <div class="activity-text"><strong>RAB Film "Sang Penakluk"</strong> diperbarui, total anggaran Rp 1.2M</div>
                  <div class="activity-time">1 jam lalu · oleh Hendra Wijaya</div>
                </div>
              </div>
              <div class="activity-item">
                <div class="activity-dot pink"></div>
                <div>
                  <div class="activity-text"><strong>Lokasi Pantai Parangtritis</strong> dikonfirmasi untuk syuting Scene 12</div>
                  <div class="activity-time">3 jam lalu · oleh Siti Nurhaliza</div>
                </div>
              </div>
              <div class="activity-item">
                <div class="activity-dot purple"></div>
                <div>
                  <div class="activity-text"><strong>Shot List Scene 7</strong> telah diselesaikan oleh Reza Gunawan</div>
                  <div class="activity-time">5 jam lalu · oleh Reza Gunawan</div>
                </div>
              </div>
              <div class="activity-item">
                <div class="activity-dot green"></div>
                <div>
                  <div class="activity-text"><strong>Meeting Pra-Produksi</strong> dijadwalkan pada 30 Juni 2026</div>
                  <div class="activity-time">1 hari lalu · oleh Budi Santoso</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar Right -->
        <div style="display:flex;flex-direction:column;gap:20px;">
          <!-- Jadwal Mendatang -->
          <div class="card">
            <div class="card-header">
              <h3><i class="fa-solid fa-calendar-days text-accent" style="margin-right:8px"></i>Jadwal Mendatang</h3>
            </div>
            <div class="card-body">
              <div class="upcoming-list">
                <div class="upcoming-item">
                  <div class="upcoming-date"><div class="day">30</div><div class="mon">Jun</div></div>
                  <div class="upcoming-info">
                    <div class="title">Meeting Pra-Produksi</div>
                    <div class="sub"><i class="fa-solid fa-clock" style="margin-right:4px"></i>09:00 WIB · Studio A</div>
                  </div>
                </div>
                <div class="upcoming-item">
                  <div class="upcoming-date"><div class="day">02</div><div class="mon">Jul</div></div>
                  <div class="upcoming-info">
                    <div class="title">Syuting Scene 1-5</div>
                    <div class="sub"><i class="fa-solid fa-map-pin" style="margin-right:4px"></i>Pantai Parangtritis</div>
                  </div>
                </div>
                <div class="upcoming-item">
                  <div class="upcoming-date"><div class="day">05</div><div class="mon">Jul</div></div>
                  <div class="upcoming-info">
                    <div class="title">Casting Pemeran Pembantu</div>
                    <div class="sub"><i class="fa-solid fa-clock" style="margin-right:4px"></i>13:00 WIB · Casting Room</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Progress Produksi -->
          <div class="card">
            <div class="card-header">
              <h3><i class="fa-solid fa-chart-line text-accent" style="margin-right:8px"></i>Progress Film Aktif</h3>
            </div>
            <div class="card-body">
              <div class="progress-list">
                <div class="progress-item">
                  <div class="prog-header">
                    <span class="prog-label">Fajar di Selatan</span>
                    <span class="prog-pct">65%</span>
                  </div>
                  <div class="progress-track"><div class="progress-bar" style="width:65%"></div></div>
                </div>
                <div class="progress-item">
                  <div class="prog-header">
                    <span class="prog-label">Sang Penakluk</span>
                    <span class="prog-pct">30%</span>
                  </div>
                  <div class="progress-track"><div class="progress-bar" style="width:30%"></div></div>
                </div>
                <div class="progress-item">
                  <div class="prog-header">
                    <span class="prog-label">Angin Malam</span>
                    <span class="prog-pct">88%</span>
                  </div>
                  <div class="progress-track"><div class="progress-bar" style="width:88%"></div></div>
                </div>
                <div class="progress-item">
                  <div class="prog-header">
                    <span class="prog-label">Batas Cakrawala</span>
                    <span class="prog-pct">12%</span>
                  </div>
                  <div class="progress-track"><div class="progress-bar" style="width:12%"></div></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Film Terbaru -->
      <div class="card">
        <div class="card-header">
          <h3><i class="fa-solid fa-clapperboard text-accent" style="margin-right:8px"></i>Film Terbaru</h3>
          <button class="btn btn-primary btn-sm" onclick="navigate('film')"><i class="fa-solid fa-arrow-right"></i> Lihat Semua</button>
        </div>
        <div class="card-body" style="padding:0">
          <table>
            <thead>
              <tr>
                <th>Film</th><th>Genre</th><th>Sutradara</th><th>Status</th><th>Progress</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><div class="film-cell"><div class="film-poster"><i class="fa-solid fa-film"></i></div><div class="film-info"><div class="name">Fajar di Selatan</div><div class="sub">2026 · Drama</div></div></div></td>
                <td><span class="badge badge-purple">Drama</span></td>
                <td>Budi Santoso</td>
                <td><span class="badge badge-green">Produksi</span></td>
                <td><div class="progress-track" style="width:100px"><div class="progress-bar" style="width:65%"></div></div></td>
              </tr>
              <tr>
                <td><div class="film-cell"><div class="film-poster"><i class="fa-solid fa-film"></i></div><div class="film-info"><div class="name">Sang Penakluk</div><div class="sub">2026 · Aksi</div></div></div></td>
                <td><span class="badge badge-amber">Aksi</span></td>
                <td>Hendra Wijaya</td>
                <td><span class="badge badge-amber">Pra-Produksi</span></td>
                <td><div class="progress-track" style="width:100px"><div class="progress-bar" style="width:30%"></div></div></td>
              </tr>
              <tr>
                <td><div class="film-cell"><div class="film-poster"><i class="fa-solid fa-film"></i></div><div class="film-info"><div class="name">Angin Malam</div><div class="sub">2025 · Thriller</div></div></div></td>
                <td><span class="badge badge-blue">Thriller</span></td>
                <td>Rina Dewi</td>
                <td><span class="badge badge-purple">Pasca-Produksi</span></td>
                <td><div class="progress-track" style="width:100px"><div class="progress-bar" style="width:88%"></div></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- =================== KELOLA FILM =================== -->
    <div class="page" id="page-film">
      <div class="section-header">
        <div>
          <h2>Kelola Film</h2>
          <p class="text-muted">Manajemen data proyek film keseluruhan</p>
        </div>
        <button class="btn btn-primary" onclick="openModal('film-modal')">
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
              <th>Tahun</th><th>Anggaran</th><th>Status</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-muted">01</td>
              <td><div class="film-cell"><div class="film-poster"><i class="fa-solid fa-film"></i></div><div class="film-info"><div class="name">Fajar di Selatan</div><div class="sub">Drama Keluarga</div></div></div></td>
              <td><span class="badge badge-purple">Drama</span></td>
              <td><div class="avatar-cell"><div class="avatar-sm">BS</div> Budi Santoso</div></td>
              <td>2026</td><td>Rp 800 Jt</td>
              <td><span class="badge badge-green">Produksi</span></td>
              <td><div class="action-btns">
                <button class="btn btn-ghost btn-sm btn-icon" onclick="viewFilm(1)" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btn-ghost btn-sm btn-icon" onclick="editFilm(1)" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
              </div></td>
            </tr>
            <tr>
              <td class="text-muted">02</td>
              <td><div class="film-cell"><div class="film-poster"><i class="fa-solid fa-film"></i></div><div class="film-info"><div class="name">Sang Penakluk</div><div class="sub">Film Aksi Petualangan</div></div></div></td>
              <td><span class="badge badge-amber">Aksi</span></td>
              <td><div class="avatar-cell"><div class="avatar-sm">HW</div> Hendra Wijaya</div></td>
              <td>2026</td><td>Rp 1.2 M</td>
              <td><span class="badge badge-amber">Pra-Produksi</span></td>
              <td><div class="action-btns">
                <button class="btn btn-ghost btn-sm btn-icon" onclick="viewFilm(2)" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btn-ghost btn-sm btn-icon" onclick="editFilm(2)" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
              </div></td>
            </tr>
            <tr>
              <td class="text-muted">03</td>
              <td><div class="film-cell"><div class="film-poster"><i class="fa-solid fa-film"></i></div><div class="film-info"><div class="name">Angin Malam</div><div class="sub">Thriller Psikologi</div></div></div></td>
              <td><span class="badge badge-blue">Thriller</span></td>
              <td><div class="avatar-cell"><div class="avatar-sm">RD</div> Rina Dewi</div></td>
              <td>2025</td><td>Rp 650 Jt</td>
              <td><span class="badge badge-purple">Pasca-Produksi</span></td>
              <td><div class="action-btns">
                <button class="btn btn-ghost btn-sm btn-icon" onclick="viewFilm(3)" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btn-ghost btn-sm btn-icon" onclick="editFilm(3)" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
              </div></td>
            </tr>
            <tr>
              <td class="text-muted">04</td>
              <td><div class="film-cell"><div class="film-poster"><i class="fa-solid fa-film"></i></div><div class="film-info"><div class="name">Batas Cakrawala</div><div class="sub">Drama Romansa</div></div></div></td>
              <td><span class="badge badge-red">Romantis</span></td>
              <td><div class="avatar-cell"><div class="avatar-sm">SN</div> Siti Nurhaliza</div></td>
              <td>2026</td><td>Rp 400 Jt</td>
              <td><span class="badge badge-gray">Development</span></td>
              <td><div class="action-btns">
                <button class="btn btn-ghost btn-sm btn-icon" onclick="viewFilm(4)" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btn-ghost btn-sm btn-icon" onclick="editFilm(4)" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
              </div></td>
            </tr>
            <tr>
              <td class="text-muted">05</td>
              <td><div class="film-cell"><div class="film-poster"><i class="fa-solid fa-film"></i></div><div class="film-info"><div class="name">Jejak Tak Berujung</div><div class="sub">Dokumenter</div></div></div></td>
              <td><span class="badge badge-green">Dokumenter</span></td>
              <td><div class="avatar-cell"><div class="avatar-sm">AR</div> Ahmad Ridwan</div></td>
              <td>2025</td><td>Rp 200 Jt</td>
              <td><span class="badge badge-gray">Selesai</span></td>
              <td><div class="action-btns">
                <button class="btn btn-ghost btn-sm btn-icon" onclick="viewFilm(5)" data-tip="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btn-ghost btn-sm btn-icon" onclick="editFilm(5)" data-tip="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)" data-tip="Hapus"><i class="fa-solid fa-trash"></i></button>
              </div></td>
            </tr>
          </tbody>
        </table>
        <div class="table-pagination">
          <span class="pagination-info">Menampilkan 1–5 dari 12 film</span>
          <div class="pagination-btns">
            <button class="pg-btn"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="pg-btn active">1</button>
            <button class="pg-btn">2</button>
            <button class="pg-btn">3</button>
            <button class="pg-btn"><i class="fa-solid fa-chevron-right"></i></button>
          </div>
        </div>
      </div>
    </div>

    <!-- =================== PLACEHOLDER PAGES =================== -->
    <div class="page" id="page-pemeran">   <div class="section-header"><div><h2>Kelola Pemeran</h2><p class="text-muted">Data seluruh pemeran film</p></div><button class="btn btn-primary" onclick="openModal('pemeran-modal')"><i class="fa-solid fa-plus"></i> Tambah Pemeran</button></div><div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari pemeran..."></div><select class="filter-select"><option>Semua Peran</option><option>Pemeran Utama</option><option>Pemeran Pembantu</option><option>Cameo</option></select></div><div class="table-wrap"><table><thead><tr><th>#</th><th>Pemeran</th><th>Peran</th><th>Film</th><th>Karakter</th><th>Usia</th><th>Status</th><th>Aksi</th></tr></thead><tbody><tr><td class="text-muted">01</td><td><div class="avatar-cell"><div class="avatar-sm">AP</div>Andika Pratama</div></td><td><span class="badge badge-purple">Pemeran Utama</span></td><td>Fajar di Selatan</td><td>Rahmat</td><td>28</td><td><span class="badge badge-green">Aktif</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('pemeran-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">02</td><td><div class="avatar-cell"><div class="avatar-sm">LM</div>Lisa Maharani</div></td><td><span class="badge badge-amber">Pemeran Utama</span></td><td>Fajar di Selatan</td><td>Sari</td><td>25</td><td><span class="badge badge-green">Aktif</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('pemeran-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">03</td><td><div class="avatar-cell"><div class="avatar-sm">DK</div>Doni Kurniawan</div></td><td><span class="badge badge-blue">Pembantu</span></td><td>Sang Penakluk</td><td>Komandan</td><td>42</td><td><span class="badge badge-amber">Casting</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('pemeran-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr></tbody></table><div class="table-pagination"><span class="pagination-info">Menampilkan 1–3 dari 48 pemeran</span><div class="pagination-btns"><button class="pg-btn active">1</button><button class="pg-btn">2</button><button class="pg-btn">...</button></div></div></div></div>

    <div class="page" id="page-crew">      <div class="section-header"><div><h2>Kelola Crew</h2><p class="text-muted">Data seluruh kru produksi film</p></div><button class="btn btn-primary" onclick="openModal('crew-modal')"><i class="fa-solid fa-plus"></i> Tambah Crew</button></div><div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari crew..."></div><select class="filter-select"><option>Semua Departemen</option><option>Sutradara</option><option>Sinematografi</option><option>Produksi</option><option>Artistik</option><option>Suara</option></select></div><div class="table-wrap"><table><thead><tr><th>#</th><th>Nama</th><th>Posisi</th><th>Departemen</th><th>Film</th><th>Kontak</th><th>Status</th><th>Aksi</th></tr></thead><tbody><tr><td class="text-muted">01</td><td><div class="avatar-cell"><div class="avatar-sm">RG</div>Reza Gunawan</div></td><td>DOP</td><td><span class="badge badge-purple">Sinematografi</span></td><td>Fajar di Selatan</td><td>0812-xxxx</td><td><span class="badge badge-green">Aktif</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('crew-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">02</td><td><div class="avatar-cell"><div class="avatar-sm">YP</div>Yuni Pertiwi</div></td><td>Art Director</td><td><span class="badge badge-amber">Artistik</span></td><td>Sang Penakluk</td><td>0813-xxxx</td><td><span class="badge badge-green">Aktif</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('crew-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">03</td><td><div class="avatar-cell"><div class="avatar-sm">FS</div>Fandi Susanto</div></td><td>Sound Mixer</td><td><span class="badge badge-blue">Suara</span></td><td>Angin Malam</td><td>0856-xxxx</td><td><span class="badge badge-gray">Selesai</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('crew-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr></tbody></table><div class="table-pagination"><span class="pagination-info">Menampilkan 1–3 dari 35 crew</span><div class="pagination-btns"><button class="pg-btn active">1</button><button class="pg-btn">2</button></div></div></div></div>

    <div class="page" id="page-properti">  <div class="section-header"><div><h2>Kebutuhan Properti Film</h2><p class="text-muted">Data properti dan peralatan produksi</p></div><button class="btn btn-primary" onclick="openModal('properti-modal')"><i class="fa-solid fa-plus"></i> Tambah Properti</button></div><div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari properti..."></div><select class="filter-select"><option>Semua Kategori</option><option>Kostum</option><option>Set Dekorasi</option><option>Peralatan Teknis</option><option>Kendaraan</option></select></div><div class="table-wrap"><table><thead><tr><th>#</th><th>Nama Properti</th><th>Kategori</th><th>Film</th><th>Jumlah</th><th>Estimasi Harga</th><th>Status</th><th>Aksi</th></tr></thead><tbody><tr><td class="text-muted">01</td><td>Seragam Tentara 1945</td><td><span class="badge badge-purple">Kostum</span></td><td>Sang Penakluk</td><td>20 set</td><td>Rp 15 Jt</td><td><span class="badge badge-amber">Proses</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('properti-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">02</td><td>Furnitur Rumah Vintage 70an</td><td><span class="badge badge-amber">Set Dekorasi</span></td><td>Fajar di Selatan</td><td>1 set</td><td>Rp 25 Jt</td><td><span class="badge badge-green">Tersedia</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('properti-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">03</td><td>Kamera Cinema 4K</td><td><span class="badge badge-blue">Peralatan</span></td><td>Angin Malam</td><td>2 unit</td><td>Rp 80 Jt</td><td><span class="badge badge-red">Dicari</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('properti-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr></tbody></table><div class="table-pagination"><span class="pagination-info">Menampilkan 1–3 dari 52 properti</span><div class="pagination-btns"><button class="pg-btn active">1</button><button class="pg-btn">2</button></div></div></div></div>

    <div class="page" id="page-rab">       <div class="section-header"><div><h2>Kelola RAB Film</h2><p class="text-muted">Rencana Anggaran Biaya produksi</p></div><button class="btn btn-primary" onclick="openModal('rab-modal')"><i class="fa-solid fa-plus"></i> Tambah Item RAB</button></div><div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari item anggaran..."></div><select class="filter-select"><option>Semua Film</option><option>Fajar di Selatan</option><option>Sang Penakluk</option></select></div><div class="table-wrap"><table><thead><tr><th>#</th><th>Nama Item</th><th>Kategori</th><th>Film</th><th>Qty</th><th>Satuan</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead><tbody><tr><td class="text-muted">01</td><td>Sewa Lokasi Pantai</td><td><span class="badge badge-blue">Lokasi</span></td><td>Fajar di Selatan</td><td>3</td><td>hari</td><td>Rp 30 Jt</td><td><span class="badge badge-green">Disetujui</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('rab-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">02</td><td>Catering Kru & Pemeran</td><td><span class="badge badge-green">Konsumsi</span></td><td>Fajar di Selatan</td><td>60</td><td>orang/hari</td><td>Rp 18 Jt</td><td><span class="badge badge-amber">Review</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('rab-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">03</td><td>Honor Pemeran Utama</td><td><span class="badge badge-purple">SDM</span></td><td>Sang Penakluk</td><td>2</td><td>orang</td><td>Rp 200 Jt</td><td><span class="badge badge-green">Disetujui</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('rab-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr></tbody></table><div class="table-pagination"><span class="pagination-info">Total: Rp 2.4 Miliar · 3 dari 47 item</span><div class="pagination-btns"><button class="pg-btn active">1</button><button class="pg-btn">2</button></div></div></div></div>

    <div class="page" id="page-lokasi">    <div class="section-header"><div><h2>Kelola Lokasi Film</h2><p class="text-muted">Data lokasi syuting dan survey</p></div><button class="btn btn-primary" onclick="openModal('lokasi-modal')"><i class="fa-solid fa-plus"></i> Tambah Lokasi</button></div><div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari lokasi..."></div><select class="filter-select"><option>Semua Tipe</option><option>Interior</option><option>Eksterior</option><option>Studio</option></select></div><div class="table-wrap"><table><thead><tr><th>#</th><th>Nama Lokasi</th><th>Tipe</th><th>Alamat</th><th>Film</th><th>Tanggal Syuting</th><th>Status</th><th>Aksi</th></tr></thead><tbody><tr><td class="text-muted">01</td><td><i class="fa-solid fa-map-pin text-accent" style="margin-right:6px"></i>Pantai Parangtritis</td><td><span class="badge badge-green">Eksterior</span></td><td>Bantul, DIY</td><td>Fajar di Selatan</td><td>02–04 Jul 2026</td><td><span class="badge badge-green">Konfirmasi</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('lokasi-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">02</td><td><i class="fa-solid fa-map-pin text-accent" style="margin-right:6px"></i>Rumah Adat Joglo</td><td><span class="badge badge-amber">Interior</span></td><td>Sleman, DIY</td><td>Fajar di Selatan</td><td>08 Jul 2026</td><td><span class="badge badge-amber">Survey</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('lokasi-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">03</td><td><i class="fa-solid fa-map-pin text-accent" style="margin-right:6px"></i>Studio B Epicentrum</td><td><span class="badge badge-blue">Studio</span></td><td>Jakarta Selatan</td><td>Sang Penakluk</td><td>15 Jul 2026</td><td><span class="badge badge-red">Negosiasi</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('lokasi-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr></tbody></table><div class="table-pagination"><span class="pagination-info">Menampilkan 1–3 dari 18 lokasi</span><div class="pagination-btns"><button class="pg-btn active">1</button><button class="pg-btn">2</button></div></div></div></div>

    <div class="page" id="page-jadwal">    <div class="section-header"><div><h2>Jadwal Produksi Film</h2><p class="text-muted">Kalender dan agenda kegiatan produksi</p></div><button class="btn btn-primary" onclick="openModal('jadwal-modal')"><i class="fa-solid fa-plus"></i> Tambah Jadwal</button></div><div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari jadwal..."></div><select class="filter-select"><option>Semua Tipe</option><option>Meeting</option><option>Syuting</option><option>Casting</option><option>Review</option></select></div><div class="table-wrap"><table><thead><tr><th>#</th><th>Kegiatan</th><th>Tipe</th><th>Film</th><th>Tanggal</th><th>Waktu</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr></thead><tbody><tr><td class="text-muted">01</td><td><i class="fa-solid fa-users text-accent" style="margin-right:6px"></i>Meeting Pra-Produksi</td><td><span class="badge badge-blue">Meeting</span></td><td>Fajar di Selatan</td><td>30 Jun 2026</td><td>09:00 WIB</td><td>Studio A</td><td><span class="badge badge-amber">Upcoming</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('jadwal-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">02</td><td><i class="fa-solid fa-video text-accent" style="margin-right:6px"></i>Syuting Scene 1-5</td><td><span class="badge badge-purple">Syuting</span></td><td>Fajar di Selatan</td><td>02 Jul 2026</td><td>06:00 WIB</td><td>Pantai Parangtritis</td><td><span class="badge badge-gray">Terjadwal</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('jadwal-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">03</td><td><i class="fa-solid fa-masks-theater text-accent" style="margin-right:6px"></i>Casting Pemeran Pembantu</td><td><span class="badge badge-green">Casting</span></td><td>Sang Penakluk</td><td>05 Jul 2026</td><td>13:00 WIB</td><td>Casting Room</td><td><span class="badge badge-gray">Terjadwal</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('jadwal-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr></tbody></table><div class="table-pagination"><span class="pagination-info">Menampilkan 1–3 dari 24 jadwal</span><div class="pagination-btns"><button class="pg-btn active">1</button><button class="pg-btn">2</button></div></div></div></div>

    <div class="page" id="page-skenario">  <div class="section-header"><div><h2>Kelola Skenario</h2><p class="text-muted">Data skrip dan naskah film</p></div><button class="btn btn-primary" onclick="openModal('skenario-modal')"><i class="fa-solid fa-plus"></i> Tambah Skenario</button></div><div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari skenario..."></div><select class="filter-select"><option>Semua Status</option><option>Draft</option><option>Revisi</option><option>Final</option></select></div><div class="table-wrap"><table><thead><tr><th>#</th><th>Judul Skenario</th><th>Film</th><th>Penulis</th><th>Versi</th><th>Halaman</th><th>Terakhir Diubah</th><th>Status</th><th>Aksi</th></tr></thead><tbody><tr><td class="text-muted">01</td><td><i class="fa-solid fa-scroll text-accent" style="margin-right:6px"></i>Skenario Utama v3</td><td>Fajar di Selatan</td><td>Budi Santoso</td><td>v3.2</td><td>98 hal</td><td>25 Jun 2026</td><td><span class="badge badge-purple">Revisi</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('skenario-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">02</td><td><i class="fa-solid fa-scroll text-accent" style="margin-right:6px"></i>Naskah Final</td><td>Angin Malam</td><td>Rina Dewi</td><td>v5.0</td><td>112 hal</td><td>10 Jun 2026</td><td><span class="badge badge-green">Final</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('skenario-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">03</td><td><i class="fa-solid fa-scroll text-accent" style="margin-right:6px"></i>Draft Awal</td><td>Batas Cakrawala</td><td>Siti Nurhaliza</td><td>v1.0</td><td>54 hal</td><td>20 Jun 2026</td><td><span class="badge badge-gray">Draft</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('skenario-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr></tbody></table><div class="table-pagination"><span class="pagination-info">Menampilkan 1–3 dari 9 skenario</span><div class="pagination-btns"><button class="pg-btn active">1</button></div></div></div></div>

    <div class="page" id="page-shotlist">   <div class="section-header"><div><h2>Kelola Shot List</h2><p class="text-muted">Daftar pengambilan gambar per scene</p></div><button class="btn btn-primary" onclick="openModal('shotlist-modal')"><i class="fa-solid fa-plus"></i> Tambah Shot</button></div><div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari shot..."></div><select class="filter-select"><option>Semua Film</option><option>Fajar di Selatan</option><option>Sang Penakluk</option></select></div><div class="table-wrap"><table><thead><tr><th>#</th><th>Scene</th><th>Shot</th><th>Film</th><th>Tipe Kamera</th><th>Durasi Est.</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr></thead><tbody><tr><td class="text-muted">01</td><td>Scene 1</td><td>Shot 1A - Establishing</td><td>Fajar di Selatan</td><td>Wide Shot</td><td>3 det</td><td>Pantai Parangtritis</td><td><span class="badge badge-green">Selesai</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('shotlist-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">02</td><td>Scene 1</td><td>Shot 1B - Close Up Wajah</td><td>Fajar di Selatan</td><td>Close Up</td><td>5 det</td><td>Pantai Parangtritis</td><td><span class="badge badge-amber">Proses</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('shotlist-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr><tr><td class="text-muted">03</td><td>Scene 2</td><td>Shot 2A - Dialog Dua Tokoh</td><td>Fajar di Selatan</td><td>Medium Shot</td><td>45 det</td><td>Rumah Adat Joglo</td><td><span class="badge badge-gray">Belum</span></td><td><div class="action-btns"><button class="btn btn-ghost btn-sm btn-icon" onclick="openModal('shotlist-modal')"><i class="fa-solid fa-pen"></i></button><button class="btn btn-danger btn-sm btn-icon" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button></div></td></tr></tbody></table><div class="table-pagination"><span class="pagination-info">Menampilkan 1–3 dari 156 shot</span><div class="pagination-btns"><button class="pg-btn active">1</button><button class="pg-btn">2</button><button class="pg-btn">...</button></div></div></div></div>

    <!-- =================== PENGATURAN =================== -->
    <div class="page" id="page-pengaturan">
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
                    <input class="form-control" type="text" value="Admin Panel">
                  </div>
                  <div class="form-group">
                    <label>Username</label>
                    <input class="form-control" type="text" value="adminpanel">
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" value="admin@cinepanel.id">
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
              </div>
            </div>
          </div>

          <!-- NOTIFIKASI -->
          <div class="settings-panel" id="set-notif">
            <div class="settings-section">
              <div class="settings-section-header"><i class="fa-solid fa-bell" style="margin-right:8px;color:var(--accent)"></i>Pengaturan Notifikasi</div>
              <div class="settings-section-body">
                <div class="setting-row"><div class="setting-info"><div class="setting-label">Notifikasi Email</div><div class="setting-desc">Kirim ringkasan aktivitas via email</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
                <div class="setting-row"><div class="setting-info"><div class="setting-label">Notifikasi Jadwal</div><div class="setting-desc">Ingatkan 1 jam sebelum jadwal kegiatan</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
                <div class="setting-row"><div class="setting-info"><div class="setting-label">Update RAB</div><div class="setting-desc">Notifikasi saat ada perubahan anggaran</div></div><div class="toggle" onclick="this.classList.toggle('on')"></div></div>
                <div class="setting-row"><div class="setting-info"><div class="setting-label">Laporan Mingguan</div><div class="setting-desc">Kirim laporan produksi setiap Senin pagi</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
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
                <div class="form-grid form-grid-2" style="margin-bottom:16px">
                  <div class="form-group">
                    <label>Nama Perusahaan</label>
                    <input class="form-control" type="text" value="CineStudio Indonesia">
                  </div>
                  <div class="form-group">
                    <label>Zona Waktu</label>
                    <select class="form-control"><option>WIB (UTC+7)</option><option>WITA (UTC+8)</option><option>WIT (UTC+9)</option></select>
                  </div>
                  <div class="form-group">
                    <label>Mata Uang</label>
                    <select class="form-control"><option>IDR - Rupiah</option><option>USD - Dollar</option></select>
                  </div>
                  <div class="form-group">
                    <label>Format Tanggal</label>
                    <select class="form-control"><option>DD/MM/YYYY</option><option>MM/DD/YYYY</option><option>YYYY-MM-DD</option></select>
                  </div>
                </div>
                <div class="setting-row"><div class="setting-info"><div class="setting-label">Mode Maintenance</div><div class="setting-desc">Nonaktifkan akses untuk user lain sementara</div></div><div class="toggle" onclick="this.classList.toggle('on')"></div></div>
                <div style="margin-top:16px">
                  <button class="btn btn-primary" onclick="showToast('Pengaturan sistem disimpan!','success')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>
</div>

<!-- ====================================================
     MODALS
     ==================================================== -->

<!-- FILM MODAL -->
<div class="modal-overlay" id="film-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-icon"><i class="fa-solid fa-clapperboard"></i></div>
        <h3 id="film-modal-title">Tambah Film Baru</h3>
      </div>
      <button class="modal-close" onclick="closeModal('film-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-divider">Informasi Utama</div>
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label>Judul Film *</label>
            <input class="form-control" type="text" placeholder="cth: Fajar di Selatan" id="fm-judul">
          </div>
          <div class="form-group">
            <label>Subtitle / Tagline</label>
            <input class="form-control" type="text" placeholder="cth: Drama Keluarga">
          </div>
          <div class="form-group">
            <label>Genre *</label>
            <select class="form-control">
              <option value="">— Pilih Genre —</option>
              <option>Drama</option><option>Aksi</option><option>Thriller</option>
              <option>Komedi</option><option>Horor</option><option>Romantis</option><option>Dokumenter</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tahun Produksi</label>
            <input class="form-control" type="number" value="2026" min="2000" max="2099">
          </div>
          <div class="form-group">
            <label>Sutradara *</label>
            <input class="form-control" type="text" placeholder="Nama sutradara">
          </div>
          <div class="form-group">
            <label>Produser</label>
            <input class="form-control" type="text" placeholder="Nama produser">
          </div>
        </div>
        <div class="form-divider">Anggaran & Status</div>
        <div class="form-grid form-grid-2">
          <div class="form-group">
            <label>Total Anggaran (Rp)</label>
            <input class="form-control" type="text" placeholder="cth: 800.000.000">
          </div>
          <div class="form-group">
            <label>Status Produksi *</label>
            <select class="form-control">
              <option value="">— Pilih Status —</option>
              <option>Development</option><option>Pra-Produksi</option>
              <option>Produksi</option><option>Pasca-Produksi</option><option>Selesai</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tanggal Mulai</label>
            <input class="form-control" type="date">
          </div>
          <div class="form-group">
            <label>Target Selesai</label>
            <input class="form-control" type="date">
          </div>
        </div>
        <div class="form-group">
          <label>Sinopsis</label>
          <textarea class="form-control" rows="3" placeholder="Deskripsi singkat tentang film..."></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('film-modal')">Batal</button>
      <button class="btn btn-primary" onclick="saveFilm()"><i class="fa-solid fa-floppy-disk"></i> Simpan Film</button>
    </div>
  </div>
</div>

<!-- PEMERAN MODAL -->
<div class="modal-overlay" id="pemeran-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-masks-theater"></i></div><h3>Tambah Pemeran</h3></div>
      <button class="modal-close" onclick="closeModal('pemeran-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid form-grid-2">
        <div class="form-group"><label>Nama Lengkap *</label><input class="form-control" type="text" placeholder="Nama pemeran"></div>
        <div class="form-group"><label>Nama Karakter *</label><input class="form-control" type="text" placeholder="Nama karakter dalam film"></div>
        <div class="form-group"><label>Film *</label><select class="form-control"><option>— Pilih Film —</option><option>Fajar di Selatan</option><option>Sang Penakluk</option><option>Angin Malam</option></select></div>
        <div class="form-group"><label>Tipe Peran</label><select class="form-control"><option>Pemeran Utama</option><option>Pemeran Pembantu</option><option>Cameo</option><option>Ekstra</option></select></div>
        <div class="form-group"><label>Usia</label><input class="form-control" type="number" placeholder="27"></div>
        <div class="form-group"><label>No. Kontak</label><input class="form-control" type="tel" placeholder="0812-xxxx-xxxx"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Agensi / Manajemen</label><input class="form-control" type="text" placeholder="Nama agensi (opsional)"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Catatan</label><textarea class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('pemeran-modal')">Batal</button>
      <button class="btn btn-primary" onclick="genericSave('pemeran-modal','Pemeran berhasil disimpan!')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- CREW MODAL -->
<div class="modal-overlay" id="crew-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-people-group"></i></div><h3>Tambah Crew</h3></div>
      <button class="modal-close" onclick="closeModal('crew-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid form-grid-2">
        <div class="form-group"><label>Nama Lengkap *</label><input class="form-control" type="text" placeholder="Nama kru"></div>
        <div class="form-group"><label>Posisi / Jabatan *</label><input class="form-control" type="text" placeholder="cth: Director of Photography"></div>
        <div class="form-group"><label>Departemen *</label><select class="form-control"><option>— Pilih —</option><option>Sutradara</option><option>Sinematografi</option><option>Produksi</option><option>Artistik</option><option>Suara</option><option>Editing</option><option>VFX</option></select></div>
        <div class="form-group"><label>Film *</label><select class="form-control"><option>— Pilih Film —</option><option>Fajar di Selatan</option><option>Sang Penakluk</option><option>Angin Malam</option></select></div>
        <div class="form-group"><label>No. Kontak</label><input class="form-control" type="tel" placeholder="0812-xxxx-xxxx"></div>
        <div class="form-group"><label>Email</label><input class="form-control" type="email" placeholder="email@domain.com"></div>
        <div class="form-group"><label>Honor (Rp)</label><input class="form-control" type="text" placeholder="cth: 15.000.000"></div>
        <div class="form-group"><label>Status</label><select class="form-control"><option>Aktif</option><option>Standby</option><option>Selesai</option></select></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('crew-modal')">Batal</button>
      <button class="btn btn-primary" onclick="genericSave('crew-modal','Crew berhasil disimpan!')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- PROPERTI MODAL -->
<div class="modal-overlay" id="properti-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-boxes-stacked"></i></div><h3>Tambah Properti</h3></div>
      <button class="modal-close" onclick="closeModal('properti-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid form-grid-2">
        <div class="form-group" style="grid-column:1/-1"><label>Nama Properti *</label><input class="form-control" type="text" placeholder="Nama properti / peralatan"></div>
        <div class="form-group"><label>Kategori *</label><select class="form-control"><option>— Pilih —</option><option>Kostum</option><option>Set Dekorasi</option><option>Peralatan Teknis</option><option>Kendaraan</option><option>Senjata/Replika</option></select></div>
        <div class="form-group"><label>Film *</label><select class="form-control"><option>— Pilih Film —</option><option>Fajar di Selatan</option><option>Sang Penakluk</option></select></div>
        <div class="form-group"><label>Jumlah</label><input class="form-control" type="number" placeholder="1"></div>
        <div class="form-group"><label>Satuan</label><input class="form-control" type="text" placeholder="unit / set / buah"></div>
        <div class="form-group"><label>Estimasi Harga (Rp)</label><input class="form-control" type="text" placeholder="cth: 5.000.000"></div>
        <div class="form-group"><label>Status</label><select class="form-control"><option>Dicari</option><option>Proses</option><option>Tersedia</option><option>Selesai</option></select></div>
        <div class="form-group" style="grid-column:1/-1"><label>Keterangan</label><textarea class="form-control" rows="2" placeholder="Detail tambahan..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('properti-modal')">Batal</button>
      <button class="btn btn-primary" onclick="genericSave('properti-modal','Properti berhasil disimpan!')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- RAB MODAL -->
<div class="modal-overlay" id="rab-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div><h3>Tambah Item RAB</h3></div>
      <button class="modal-close" onclick="closeModal('rab-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid form-grid-2">
        <div class="form-group" style="grid-column:1/-1"><label>Nama Item *</label><input class="form-control" type="text" placeholder="Nama item anggaran"></div>
        <div class="form-group"><label>Kategori *</label><select class="form-control"><option>— Pilih —</option><option>Lokasi</option><option>SDM</option><option>Peralatan</option><option>Konsumsi</option><option>Transportasi</option><option>Marketing</option><option>Lain-lain</option></select></div>
        <div class="form-group"><label>Film *</label><select class="form-control"><option>— Pilih Film —</option><option>Fajar di Selatan</option><option>Sang Penakluk</option></select></div>
        <div class="form-group"><label>Qty</label><input class="form-control" type="number" placeholder="1"></div>
        <div class="form-group"><label>Satuan</label><input class="form-control" type="text" placeholder="hari / orang / unit"></div>
        <div class="form-group"><label>Harga Satuan (Rp)</label><input class="form-control" type="text" placeholder="cth: 5.000.000"></div>
        <div class="form-group"><label>Total (Rp)</label><input class="form-control" type="text" placeholder="Otomatis terhitung"></div>
        <div class="form-group"><label>Status</label><select class="form-control"><option>Draft</option><option>Review</option><option>Disetujui</option><option>Ditolak</option></select></div>
        <div class="form-group" style="grid-column:1/-1"><label>Keterangan</label><textarea class="form-control" rows="2" placeholder="Catatan anggaran..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('rab-modal')">Batal</button>
      <button class="btn btn-primary" onclick="genericSave('rab-modal','Item RAB berhasil disimpan!')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- LOKASI MODAL -->
<div class="modal-overlay" id="lokasi-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-map-location-dot"></i></div><h3>Tambah Lokasi</h3></div>
      <button class="modal-close" onclick="closeModal('lokasi-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid form-grid-2">
        <div class="form-group" style="grid-column:1/-1"><label>Nama Lokasi *</label><input class="form-control" type="text" placeholder="cth: Pantai Parangtritis"></div>
        <div class="form-group"><label>Tipe *</label><select class="form-control"><option>Eksterior</option><option>Interior</option><option>Studio</option></select></div>
        <div class="form-group"><label>Film *</label><select class="form-control"><option>— Pilih Film —</option><option>Fajar di Selatan</option><option>Sang Penakluk</option></select></div>
        <div class="form-group" style="grid-column:1/-1"><label>Alamat Lengkap</label><input class="form-control" type="text" placeholder="Alamat lokasi syuting"></div>
        <div class="form-group"><label>Tanggal Mulai Syuting</label><input class="form-control" type="date"></div>
        <div class="form-group"><label>Tanggal Selesai</label><input class="form-control" type="date"></div>
        <div class="form-group"><label>Biaya Sewa (Rp)</label><input class="form-control" type="text" placeholder="0"></div>
        <div class="form-group"><label>Status</label><select class="form-control"><option>Survey</option><option>Negosiasi</option><option>Konfirmasi</option><option>Selesai</option></select></div>
        <div class="form-group" style="grid-column:1/-1"><label>Catatan Lokasi</label><textarea class="form-control" rows="2" placeholder="Akses, perizinan, kondisi lokasi..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('lokasi-modal')">Batal</button>
      <button class="btn btn-primary" onclick="genericSave('lokasi-modal','Lokasi berhasil disimpan!')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- JADWAL MODAL -->
<div class="modal-overlay" id="jadwal-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-calendar-days"></i></div><h3>Tambah Jadwal</h3></div>
      <button class="modal-close" onclick="closeModal('jadwal-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid form-grid-2">
        <div class="form-group" style="grid-column:1/-1"><label>Nama Kegiatan *</label><input class="form-control" type="text" placeholder="cth: Meeting Pra-Produksi"></div>
        <div class="form-group"><label>Tipe Kegiatan *</label><select class="form-control"><option>Meeting</option><option>Syuting</option><option>Casting</option><option>Review</option><option>Training</option><option>Lainnya</option></select></div>
        <div class="form-group"><label>Film *</label><select class="form-control"><option>— Pilih Film —</option><option>Fajar di Selatan</option><option>Sang Penakluk</option><option>Angin Malam</option></select></div>
        <div class="form-group"><label>Tanggal *</label><input class="form-control" type="date"></div>
        <div class="form-group"><label>Waktu Mulai</label><input class="form-control" type="time" value="09:00"></div>
        <div class="form-group"><label>Waktu Selesai</label><input class="form-control" type="time" value="17:00"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Lokasi Kegiatan</label><input class="form-control" type="text" placeholder="Tempat pelaksanaan kegiatan"></div>
        <div class="form-group"><label>PIC</label><input class="form-control" type="text" placeholder="Penanggung jawab"></div>
        <div class="form-group"><label>Status</label><select class="form-control"><option>Terjadwal</option><option>Upcoming</option><option>Berlangsung</option><option>Selesai</option><option>Dibatalkan</option></select></div>
        <div class="form-group" style="grid-column:1/-1"><label>Deskripsi / Agenda</label><textarea class="form-control" rows="2" placeholder="Detail agenda kegiatan..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('jadwal-modal')">Batal</button>
      <button class="btn btn-primary" onclick="genericSave('jadwal-modal','Jadwal berhasil disimpan!')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- SKENARIO MODAL -->
<div class="modal-overlay" id="skenario-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-scroll"></i></div><h3>Tambah Skenario</h3></div>
      <button class="modal-close" onclick="closeModal('skenario-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid form-grid-2">
        <div class="form-group" style="grid-column:1/-1"><label>Judul Skenario *</label><input class="form-control" type="text" placeholder="cth: Skenario Utama v1"></div>
        <div class="form-group"><label>Film *</label><select class="form-control"><option>— Pilih Film —</option><option>Fajar di Selatan</option><option>Sang Penakluk</option></select></div>
        <div class="form-group"><label>Penulis *</label><input class="form-control" type="text" placeholder="Nama penulis skenario"></div>
        <div class="form-group"><label>Versi</label><input class="form-control" type="text" placeholder="cth: v1.0"></div>
        <div class="form-group"><label>Jumlah Halaman</label><input class="form-control" type="number" placeholder="0"></div>
        <div class="form-group"><label>Status</label><select class="form-control"><option>Draft</option><option>Revisi</option><option>Final</option></select></div>
        <div class="form-group" style="grid-column:1/-1"><label>Catatan Revisi</label><textarea class="form-control" rows="2" placeholder="Catatan perubahan pada versi ini..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('skenario-modal')">Batal</button>
      <button class="btn btn-primary" onclick="genericSave('skenario-modal','Skenario berhasil disimpan!')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- SHOT LIST MODAL -->
<div class="modal-overlay" id="shotlist-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-list-check"></i></div><h3>Tambah Shot</h3></div>
      <button class="modal-close" onclick="closeModal('shotlist-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid form-grid-2">
        <div class="form-group"><label>Film *</label><select class="form-control"><option>— Pilih Film —</option><option>Fajar di Selatan</option><option>Sang Penakluk</option></select></div>
        <div class="form-group"><label>Scene *</label><input class="form-control" type="text" placeholder="cth: Scene 1"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Nama / Deskripsi Shot *</label><input class="form-control" type="text" placeholder="cth: Shot 1A - Establishing Wide Shot"></div>
        <div class="form-group"><label>Tipe Kamera *</label><select class="form-control"><option>Wide Shot</option><option>Medium Shot</option><option>Close Up</option><option>Extreme Close Up</option><option>Over the Shoulder</option><option>POV</option><option>Aerial</option></select></div>
        <div class="form-group"><label>Pergerakan Kamera</label><select class="form-control"><option>Static</option><option>Pan</option><option>Tilt</option><option>Dolly</option><option>Handheld</option><option>Steadicam</option><option>Drone</option></select></div>
        <div class="form-group"><label>Durasi Estimasi</label><input class="form-control" type="text" placeholder="cth: 5 detik"></div>
        <div class="form-group"><label>Lokasi Shot</label><input class="form-control" type="text" placeholder="Nama lokasi pengambilan gambar"></div>
        <div class="form-group"><label>Status</label><select class="form-control"><option>Belum</option><option>Proses</option><option>Selesai</option><option>Revisi</option></select></div>
        <div class="form-group" style="grid-column:1/-1"><label>Catatan Sutradara</label><textarea class="form-control" rows="2" placeholder="Instruksi atau catatan khusus..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('shotlist-modal')">Batal</button>
      <button class="btn btn-primary" onclick="genericSave('shotlist-modal','Shot berhasil disimpan!')"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </div>
</div>

<!-- VIEW DETAIL FILM MODAL -->
<div class="modal-overlay" id="view-film-modal">
  <div class="modal" style="max-width:640px">
    <div class="modal-header">
      <div class="modal-header-left"><div class="modal-icon"><i class="fa-solid fa-eye"></i></div><h3>Detail Film</h3></div>
      <button class="modal-close" onclick="closeModal('view-film-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body" id="view-film-body">
      <!-- dynamic content -->
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('view-film-modal')">Tutup</button>
      <button class="btn btn-primary" onclick="closeModal('view-film-modal');openModal('film-modal')"><i class="fa-solid fa-pen"></i> Edit Film</button>
    </div>
  </div>
</div>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toastContainer"></div>

<!-- ====================================================
     JAVASCRIPT
     ==================================================== -->
<script>
/* ---- STATE ---- */
let isDark = true;
let sidebarCollapsed = false;
let currentPage = 'dashboard';

const pageLabels = {
  dashboard:   ['Dashboard', '/ Overview'],
  film:        ['Kelola Film', '/ Manajemen Proyek Film'],
  pemeran:     ['Kelola Pemeran', '/ Data Aktor & Aktris'],
  crew:        ['Kelola Crew', '/ Tim Produksi'],
  properti:    ['Kebutuhan Properti', '/ Manajemen Properti'],
  rab:         ['Kelola RAB', '/ Rencana Anggaran Biaya'],
  lokasi:      ['Kelola Lokasi', '/ Data Lokasi Syuting'],
  jadwal:      ['Jadwal Produksi', '/ Kalender Kegiatan'],
  skenario:    ['Kelola Skenario', '/ Naskah & Skrip'],
  shotlist:    ['Kelola Shot List', '/ Daftar Pengambilan Gambar'],
  pengaturan:  ['Pengaturan', '/ Konfigurasi Aplikasi'],
};

/* ---- NAVIGATION ---- */
function navigate(page) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));

  const target = document.getElementById('page-' + page);
  if (target) target.classList.add('active');

  const navItems = document.querySelectorAll('.nav-item');
  navItems.forEach(item => {
    if (item.getAttribute('onclick') && item.getAttribute('onclick').includes("'" + page + "'")) {
      item.classList.add('active');
    }
  });

  const labels = pageLabels[page] || [page, ''];
  document.getElementById('topbarTitle').innerHTML = labels[0] + ' <small>' + labels[1] + '</small>';
  currentPage = page;

  // mobile: close sidebar
  if (window.innerWidth <= 768) {
    document.getElementById('sidebar').classList.remove('mobile-open');
  }
}

/* ---- SIDEBAR ---- */
function toggleSidebar() {
  const sb = document.getElementById('sidebar');
  const mw = document.getElementById('mainWrap');
  const icon = document.getElementById('toggleIcon');

  if (window.innerWidth <= 768) {
    sb.classList.toggle('mobile-open');
    return;
  }

  sidebarCollapsed = !sidebarCollapsed;
  sb.classList.toggle('collapsed', sidebarCollapsed);
  mw.classList.toggle('expanded', sidebarCollapsed);
  icon.className = sidebarCollapsed
    ? 'fa-solid fa-angles-right'
    : 'fa-solid fa-angles-left';
}

/* ---- THEME ---- */
function toggleTheme() {
  isDark = !isDark;
  document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
  document.getElementById('themeIcon').className = isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
  // sync settings toggle
  const dt = document.getElementById('darkToggle');
  if (dt) dt.classList.toggle('on', isDark);
}
function toggleThemeFromSettings(el) {
  el.classList.toggle('on');
  isDark = el.classList.contains('on');
  document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
  document.getElementById('themeIcon').className = isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
}

/* ---- MODAL ---- */
function openModal(id) {
  document.getElementById(id).classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
  document.body.style.overflow = '';
}
// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) {
    if (e.target === this) {
      this.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
});

/* ---- TOAST ---- */
function showToast(msg, type = 'info') {
  const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', info: 'fa-circle-info' };
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<i class="fa-solid ${icons[type]}"></i> ${msg}`;
  document.getElementById('toastContainer').appendChild(toast);
  setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(40px)'; toast.style.transition = '.3s'; }, 2800);
  setTimeout(() => toast.remove(), 3200);
}

/* ---- FILM ACTIONS ---- */
const filmData = {
  1: { judul: 'Fajar di Selatan', genre: 'Drama', sutradara: 'Budi Santoso', tahun: 2026, anggaran: 'Rp 800 Jt', status: 'Produksi', sinopsis: 'Kisah perjalanan hidup seorang ayah yang berjuang membangun keluarganya di pedesaan Jawa.' },
  2: { judul: 'Sang Penakluk', genre: 'Aksi', sutradara: 'Hendra Wijaya', tahun: 2026, anggaran: 'Rp 1.2 M', status: 'Pra-Produksi', sinopsis: 'Aksi heroik seorang pejuang kemerdekaan Indonesia dalam melawan penjajah.' },
  3: { judul: 'Angin Malam', genre: 'Thriller', sutradara: 'Rina Dewi', tahun: 2025, anggaran: 'Rp 650 Jt', status: 'Pasca-Produksi', sinopsis: 'Seorang psikolog terjebak dalam misteri gelap yang mengancam nyawanya sendiri.' },
  4: { judul: 'Batas Cakrawala', genre: 'Romantis', sutradara: 'Siti Nurhaliza', tahun: 2026, anggaran: 'Rp 400 Jt', status: 'Development', sinopsis: 'Kisah cinta dua insan yang dipisahkan oleh batas budaya dan keyakinan.' },
  5: { judul: 'Jejak Tak Berujung', genre: 'Dokumenter', sutradara: 'Ahmad Ridwan', tahun: 2025, anggaran: 'Rp 200 Jt', status: 'Selesai', sinopsis: 'Perjalanan mendokumentasikan kehidupan komunitas adat terpencil di pedalaman Kalimantan.' },
};
function viewFilm(id) {
  const f = filmData[id];
  if (!f) return;
  const statusClass = { Produksi:'badge-green', 'Pra-Produksi':'badge-amber', 'Pasca-Produksi':'badge-purple', Development:'badge-gray', Selesai:'badge-gray' }[f.status] || 'badge-gray';
  document.getElementById('view-film-body').innerHTML = `
    <div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:20px">
      <div style="width:70px;height:90px;border-radius:10px;background:var(--bg-4);display:grid;place-items:center;font-size:28px;color:var(--accent);flex-shrink:0;border:1px solid var(--border)"><i class="fa-solid fa-film"></i></div>
      <div>
        <div style="font-size:20px;font-weight:800;margin-bottom:4px">${f.judul}</div>
        <div style="font-size:13px;color:var(--text-2)">${f.genre} · ${f.tahun}</div>
        <div style="margin-top:8px"><span class="badge ${statusClass}">${f.status}</span></div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
      <div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">SUTRADARA</div><div style="font-size:14px;font-weight:600">${f.sutradara}</div></div>
      <div style="background:var(--bg-3);border-radius:8px;padding:12px"><div style="font-size:11px;color:var(--text-3);margin-bottom:3px">TOTAL ANGGARAN</div><div style="font-size:14px;font-weight:600">${f.anggaran}</div></div>
    </div>
    <div style="background:var(--bg-3);border-radius:8px;padding:14px">
      <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:8px">SINOPSIS</div>
      <div style="font-size:13px;line-height:1.7;color:var(--text-2)">${f.sinopsis}</div>
    </div>`;
  openModal('view-film-modal');
}
function editFilm(id) {
  const f = filmData[id];
  if (!f) return;
  document.getElementById('film-modal-title').textContent = 'Edit Film — ' + f.judul;
  document.getElementById('fm-judul').value = f.judul;
  openModal('film-modal');
}
function saveFilm() {
  const judul = document.getElementById('fm-judul').value.trim();
  if (!judul) { showToast('Judul film wajib diisi!', 'error'); return; }
  closeModal('film-modal');
  showToast('Film "' + judul + '" berhasil disimpan!', 'success');
}
function genericSave(modalId, msg) {
  closeModal(modalId);
  showToast(msg, 'success');
}

/* ---- DELETE ROW ---- */
function deleteRow(btn) {
  if (!confirm('Yakin ingin menghapus data ini?')) return;
  const row = btn.closest('tr');
  row.style.transition = 'opacity .3s,transform .3s';
  row.style.opacity = '0';
  row.style.transform = 'translateX(20px)';
  setTimeout(() => { row.remove(); showToast('Data berhasil dihapus.', 'success'); }, 300);
}

/* ---- TABLE FILTER ---- */
function filterTable(input, tableId) {
  const q = input.value.toLowerCase();
  document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
function filterByStatus(select, tableId) {
  const q = select.value.toLowerCase();
  document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
    row.style.display = (!q || row.textContent.toLowerCase().includes(q)) ? '' : 'none';
  });
}

/* ---- SETTINGS ---- */
function switchSettings(el, panelId) {
  document.querySelectorAll('.settings-menu-item').forEach(i => i.classList.remove('active'));
  document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.getElementById(panelId).classList.add('active');
}

/* ---- COLOR SWATCH ---- */
function selectSwatch(el) {
  document.querySelectorAll('.theme-swatch').forEach(s => s.classList.remove('selected'));
  el.classList.add('selected');
  const color = el.style.background;
  document.documentElement.style.setProperty('--accent', color);
  document.documentElement.style.setProperty('--accent-hover', color);
  showToast('Warna aksen diperbarui!', 'success');
}

/* ---- ESC KEY ---- */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(m => {
      m.classList.remove('open');
      document.body.style.overflow = '';
    });
  }
});

/* ---- INIT ---- */
window.addEventListener('DOMContentLoaded', () => {
  // Show initial toast after short delay
  setTimeout(() => showToast('Selamat datang di CinePanel! 🎬', 'info'), 600);
});
</script>
</body>
</html>