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
:root {
  --purple-400: #a78bfa;
  --purple-500: #8b5cf6;
  --purple-600: #7c3aed;
  --purple-700: #6d28d9;
  --purple-800: #5b21b6;
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
[data-theme="dark"] {
  --bg: #0d0d14; --bg-2: #13131f; --bg-3: #1a1a2e; --bg-4: #21213a;
  --border: rgba(255,255,255,.07); --border-2: rgba(255,255,255,.12);
  --text-1: #f0efff; --text-2: #9b99c0; --text-3: #5e5c7a;
  --shadow: 0 4px 24px rgba(0,0,0,.55); --shadow-sm: 0 2px 10px rgba(0,0,0,.35);
  --badge-bg: rgba(255,255,255,.06);
}
[data-theme="light"] {
  --bg: #f4f2ff; --bg-2: #ffffff; --bg-3: #ece9ff; --bg-4: #ddd8ff;
  --border: rgba(0,0,0,.07); --border-2: rgba(0,0,0,.13);
  --text-1: #1a1740; --text-2: #5b567a; --text-3: #9b96bc;
  --shadow: 0 4px 24px rgba(100,80,200,.12); --shadow-sm: 0 2px 10px rgba(100,80,200,.08);
  --badge-bg: rgba(0,0,0,.05);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: var(--font); background: var(--bg); color: var(--text-1);
  min-height: 100vh; display: flex; overflow-x: hidden;
  transition: background var(--transition), color var(--transition);
}
a { text-decoration: none; color: inherit; }
button { font-family: var(--font); cursor: pointer; border: none; background: none; }
input, select, textarea { font-family: var(--font); }
ul { list-style: none; }
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--bg-4); border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: var(--accent); }
.sidebar {
  width: var(--sidebar-w); height: 100vh; background: var(--bg-2);
  border-right: 1px solid var(--border); display: flex; flex-direction: column;
  position: fixed; top: 0; left: 0; z-index: 200;
  transition: width var(--transition), transform var(--transition), background var(--transition);
}
.sidebar.collapsed { width: 68px; }
.sidebar-logo {
  display: flex; align-items: center; gap: 12px;
  padding: 20px 18px; border-bottom: 1px solid var(--border); min-height: var(--topbar-h);
}
.logo-icon {
  width: 36px; height: 36px;
  background: linear-gradient(135deg, var(--accent), #fb923c);
  border-radius: 10px; display: grid; place-items: center;
  font-size: 16px; color: #fff; flex-shrink: 0; box-shadow: 0 0 18px var(--accent-glow);
}
.logo-text { font-size: 17px; font-weight: 700; color: var(--text-1); white-space: nowrap; overflow: hidden; transition: opacity var(--transition), width var(--transition); }
.logo-text span { color: var(--accent); }
.sidebar.collapsed .logo-text { opacity: 0; width: 0; }
.sidebar-toggle {
  margin-left: auto; width: 28px; height: 28px; border-radius: 6px;
  background: var(--badge-bg); color: var(--text-2);
  display: grid; place-items: center; font-size: 12px;
  transition: background var(--transition), color var(--transition); flex-shrink: 0;
}
.sidebar-toggle:hover { background: var(--accent-soft); color: var(--accent); }
.sidebar.collapsed .sidebar-toggle { margin-left: 0; }
.sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 12px 0; }
.nav-group { margin-bottom: 4px; }
.nav-group-label {
  font-size: 10px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
  color: var(--text-3); padding: 10px 20px 4px; white-space: nowrap; overflow: hidden;
  transition: opacity var(--transition);
}
.sidebar.collapsed .nav-group-label { opacity: 0; }
.nav-item {
  display: flex; align-items: center; gap: 12px; padding: 10px 18px;
  border-radius: var(--radius-sm); margin: 2px 8px; cursor: pointer;
  transition: background var(--transition), color var(--transition); position: relative;
  white-space: nowrap; color: var(--text-2); font-size: 13.5px; font-weight: 500;
}
.nav-item:hover { background: var(--accent-soft); color: var(--accent); }
.nav-item.active { background: var(--accent-soft); color: var(--accent); }
.nav-item.active::before {
  content: ''; position: absolute; left: -8px; top: 50%; transform: translateY(-50%);
  width: 3px; height: 20px; background: var(--accent); border-radius: 0 3px 3px 0;
}
.nav-item i:first-child { width: 20px; text-align: center; font-size: 15px; flex-shrink: 0; }
.nav-label { overflow: hidden; transition: opacity var(--transition), width var(--transition); }
.sidebar.collapsed .nav-label { opacity: 0; width: 0; pointer-events: none; }
.nav-badge {
  margin-left: auto; background: var(--accent); color: #fff;
  font-size: 10px; font-weight: 700; padding: 1px 7px; border-radius: 20px; flex-shrink: 0;
}
.sidebar.collapsed .nav-badge { display: none; }
.sidebar-footer {
  border-top: 1px solid var(--border); padding: 14px 18px;
  display: flex; align-items: center; gap: 10px;
}
.avatar {
  width: 34px; height: 34px; border-radius: 50%;
  background: linear-gradient(135deg, var(--accent), #fb923c);
  display: grid; place-items: center; font-size: 13px; color: #fff; font-weight: 700; flex-shrink: 0;
}
.user-info { overflow: hidden; }
.user-name { font-size: 13px; font-weight: 600; white-space: nowrap; }
.user-role { font-size: 11px; color: var(--text-3); white-space: nowrap; }
.sidebar.collapsed .user-info { display: none; }
.main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; transition: margin-left var(--transition); }
.main-wrap.expanded { margin-left: 68px; }
.topbar {
  height: var(--topbar-h); background: var(--bg-2); border-bottom: 1px solid var(--border);
  display: flex; align-items: center; padding: 0 24px; gap: 16px;
  position: sticky; top: 0; z-index: 100; transition: background var(--transition);
}
.topbar-title { font-size: 16px; font-weight: 700; flex: 1; }
.topbar-title small { font-size: 12px; color: var(--text-3); font-weight: 400; margin-left: 6px; }
.topbar-actions { display: flex; align-items: center; gap: 10px; }
.icon-btn {
  width: 36px; height: 36px; border-radius: var(--radius-sm);
  background: var(--badge-bg); color: var(--text-2);
  display: grid; place-items: center; font-size: 14px;
  transition: background var(--transition), color var(--transition); position: relative;
}
.icon-btn:hover { background: var(--accent-soft); color: var(--accent); }
.icon-btn .notif-dot {
  position: absolute; top: 6px; right: 6px; width: 7px; height: 7px;
  background: var(--accent); border-radius: 50%; border: 2px solid var(--bg-2);
}
.page-content { padding: 28px 28px 40px; flex: 1; }
.page { display: none; }
.page.active { display: block; }
.welcome-banner {
  background: linear-gradient(135deg, #c2410c 0%, var(--accent) 60%, #fb923c 100%);
  border-radius: var(--radius-lg); padding: 28px 32px;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 28px; position: relative; overflow: hidden;
  box-shadow: 0 8px 32px var(--accent-glow);
}
.welcome-banner::before {
  content: ''; position: absolute; top: -40px; right: 80px;
  width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,.06);
}
.welcome-banner::after {
  content: ''; position: absolute; bottom: -60px; right: 20px;
  width: 160px; height: 160px; border-radius: 50%; background: rgba(255,255,255,.04);
}
.welcome-text h2 { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 6px; }
.welcome-text p { font-size: 13px; color: rgba(255,255,255,.75); }
.welcome-icon { font-size: 64px; color: rgba(255,255,255,.2); position: relative; z-index: 1; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px; }
.stat-card {
  background: var(--bg-2); border: 1px solid var(--border); border-radius: var(--radius-md);
  padding: 20px; display: flex; align-items: center; gap: 16px;
  transition: border-color var(--transition), transform .2s; box-shadow: var(--shadow-sm);
}
.stat-card:hover { border-color: var(--accent); transform: translateY(-2px); }
.stat-icon {
  width: 48px; height: 48px; border-radius: var(--radius-sm);
  display: grid; place-items: center; font-size: 20px; flex-shrink: 0;
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
.dash-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px; }
@media (max-width: 1024px) { .dash-grid { grid-template-columns: 1fr; } }
.card { background: var(--bg-2); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: background var(--transition); display: flex; flex-direction: column; }
.card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.card-header h3 { font-size: 14px; font-weight: 700; }
.card-header a, .card-header button.link { font-size: 12px; color: var(--accent); font-weight: 500; background: none; border: none; cursor: pointer; }
.card-body { padding: 16px 20px; flex: 1; display: flex; flex-direction: column; }
.activity-list { display: flex; flex-direction: column; gap: 14px; flex: 1; }
.activity-item { display: flex; gap: 12px; align-items: flex-start; }
.activity-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
.activity-dot.purple { background: var(--accent); }
.activity-dot.green  { background: #10b981; }
.activity-dot.amber  { background: #f59e0b; }
.activity-dot.pink   { background: #ec4899; }
.activity-text { font-size: 13px; line-height: 1.5; }
.activity-text strong { font-weight: 600; }
.activity-time { font-size: 11px; color: var(--text-3); margin-top: 2px; }
.upcoming-list { display: flex; flex-direction: column; gap: 12px; flex: 1; }
.upcoming-item { display: flex; gap: 12px; align-items: center; padding: 10px 12px; border-radius: var(--radius-sm); background: var(--bg-3); border: 1px solid var(--border); }
.upcoming-date { text-align: center; min-width: 40px; }
.upcoming-date .day { font-size: 20px; font-weight: 800; color: var(--accent); line-height: 1; }
.upcoming-date .mon { font-size: 10px; color: var(--text-3); text-transform: uppercase; }
.upcoming-info .title { font-size: 13px; font-weight: 600; }
.upcoming-info .sub  { font-size: 11px; color: var(--text-2); margin-top: 1px; }
.progress-list { display: flex; flex-direction: column; gap: 14px; flex: 1; }
.progress-item .prog-header { display: flex; justify-content: space-between; margin-bottom: 6px; }
.progress-item .prog-label { font-size: 13px; font-weight: 500; }
.progress-item .prog-pct { font-size: 12px; color: var(--text-2); }
.progress-track { height: 6px; background: var(--bg-4); border-radius: 99px; overflow: hidden; }
.progress-bar { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--accent), #fb923c); transition: width .8s cubic-bezier(.4,0,.2,1); }
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
.section-header h2 { font-size: 18px; font-weight: 700; }
.section-header p  { font-size: 13px; color: var(--text-2); margin-top: 2px; }
.toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
.search-box { display: flex; align-items: center; gap: 8px; background: var(--bg-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 8px 14px; flex: 1; min-width: 200px; transition: border-color var(--transition); }
.search-box:focus-within { border-color: var(--accent); }
.search-box i { color: var(--text-3); font-size: 13px; }
.search-box input { border: none; background: none; color: var(--text-1); font-size: 13px; outline: none; width: 100%; }
.search-box input::placeholder { color: var(--text-3); }
.filter-select { background: var(--bg-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 8px 14px; color: var(--text-1); font-size: 13px; outline: none; cursor: pointer; transition: border-color var(--transition); }
.filter-select:focus { border-color: var(--accent); }
.table-wrap { background: var(--bg-2); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); }
table { width: 100%; border-collapse: collapse; }
thead th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-3); background: var(--bg-3); border-bottom: 1px solid var(--border); }
tbody tr { border-bottom: 1px solid var(--border); transition: background var(--transition); }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--bg-3); }
tbody td { padding: 12px 16px; font-size: 13px; color: var(--text-1); vertical-align: middle; }
.table-pagination { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; border-top: 1px solid var(--border); background: var(--bg-2); }
.pagination-info { font-size: 12px; color: var(--text-2); }
.pagination-btns { display: flex; gap: 6px; }
.pg-btn { width: 30px; height: 30px; border-radius: 6px; background: var(--bg-3); color: var(--text-2); font-size: 12px; display: grid; place-items: center; border: 1px solid var(--border); transition: all var(--transition); }
.pg-btn:hover, .pg-btn.active { background: var(--accent); color: #fff; border-color: var(--accent); }
.badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
.badge-purple { background: var(--accent-soft); color: var(--accent); }
.badge-green  { background: rgba(16,185,129,.15); color: #10b981; }
.badge-amber  { background: rgba(245,158,11,.15);  color: #f59e0b; }
.badge-red    { background: rgba(239,68,68,.15);   color: #ef4444; }
.badge-blue   { background: rgba(59,130,246,.15);  color: #3b82f6; }
.badge-gray   { background: var(--badge-bg); color: var(--text-2); }
.film-cell { display: flex; align-items: center; gap: 10px; }
.film-poster { width: 38px; height: 50px; border-radius: 6px; object-fit: cover; background: var(--bg-4); display: grid; place-items: center; color: var(--text-3); font-size: 16px; flex-shrink: 0; border: 1px solid var(--border); }
.film-info .name { font-size: 13px; font-weight: 600; }
.film-info .sub  { font-size: 11px; color: var(--text-2); margin-top: 1px; }
.avatar-cell { display: flex; align-items: center; gap: 8px; }
.avatar-sm { width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), #fb923c); display: grid; place-items: center; font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0; }
.btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; transition: all var(--transition); cursor: pointer; border: none; }
.btn-primary { background: var(--accent); color: #fff; box-shadow: 0 4px 14px var(--accent-glow); }
.btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); }
.btn-outline { background: none; border: 1px solid var(--border-2); color: var(--text-2); }
.btn-outline:hover { border-color: var(--accent); color: var(--accent); }
.btn-ghost { background: var(--badge-bg); color: var(--text-2); }
.btn-ghost:hover { background: var(--accent-soft); color: var(--accent); }
.btn-danger { background: rgba(239,68,68,.15); color: #ef4444; }
.btn-danger:hover { background: rgba(239,68,68,.25); }
.btn-sm { padding: 5px 12px; font-size: 12px; }
.btn-icon { width: 30px; height: 30px; padding: 0; display: grid; place-items: center; }
.action-btns { display: flex; gap: 6px; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.65); backdrop-filter: blur(4px); z-index: 500; display: none; align-items: center; justify-content: center; padding: 20px; }
.modal-overlay.open { display: flex; }
.modal { background: var(--bg-2); border: 1px solid var(--border-2); border-radius: var(--radius-lg); width: 100%; max-width: 560px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: var(--shadow); animation: modalIn .25s cubic-bezier(.34,1.56,.64,1); }
@keyframes modalIn { from { opacity: 0; transform: scale(.92) translateY(12px); } to { opacity: 1; transform: scale(1) translateY(0); } }
.modal form { display: flex; flex-direction: column; flex: 1; min-height: 0; }
.modal-header { padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.modal-header h3 { font-size: 16px; font-weight: 700; }
.modal-header .modal-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--accent-soft); color: var(--accent); display: grid; place-items: center; font-size: 15px; margin-right: 10px; }
.modal-header-left { display: flex; align-items: center; }
.modal-close { width: 30px; height: 30px; border-radius: 6px; background: var(--badge-bg); color: var(--text-2); display: grid; place-items: center; font-size: 13px; transition: all var(--transition); }
.modal-close:hover { background: rgba(239,68,68,.15); color: #ef4444; }
.modal-body { padding: 20px 24px; overflow-y: auto; flex: 1; min-height: 0; }
.modal-footer { padding: 14px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }
.form-grid { display: grid; gap: 16px; }
.form-grid-2 { grid-template-columns: 1fr 1fr; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 12px; font-weight: 600; color: var(--text-2); }
.form-control { background: var(--bg-3); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 9px 13px; color: var(--text-1); font-size: 13px; outline: none; transition: border-color var(--transition); width: 100%; }
.form-control:focus { border-color: var(--accent); }
.form-control::placeholder { color: var(--text-3); }
textarea.form-control { resize: vertical; min-height: 80px; }
select.form-control { cursor: pointer; }
.form-divider { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--text-3); padding: 4px 0; border-bottom: 1px solid var(--border); margin-bottom: 4px; }
.settings-grid { display: grid; grid-template-columns: 240px 1fr; gap: 24px; align-items: start; }
@media (max-width: 800px) { .settings-grid { grid-template-columns: 1fr; } }
.settings-menu { background: var(--bg-2); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; }
.settings-menu-item { display: flex; align-items: center; gap: 10px; padding: 12px 16px; font-size: 13px; font-weight: 500; cursor: pointer; border-bottom: 1px solid var(--border); color: var(--text-2); transition: all var(--transition); }
.settings-menu-item:last-child { border-bottom: none; }
.settings-menu-item:hover { background: var(--bg-3); color: var(--accent); }
.settings-menu-item.active { background: var(--accent-soft); color: var(--accent); font-weight: 600; }
.settings-menu-item i { width: 18px; text-align: center; font-size: 13px; }
.settings-panel { display: none; }
.settings-panel.active { display: block; }
.settings-section { background: var(--bg-2); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; margin-bottom: 20px; }
.settings-section-header { padding: 14px 20px; border-bottom: 1px solid var(--border); font-size: 14px; font-weight: 700; background: var(--bg-3); }
.settings-section-body { padding: 20px; }
.setting-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border); }
.setting-row:last-child { border-bottom: none; }
.setting-info .setting-label { font-size: 13px; font-weight: 600; }
.setting-info .setting-desc  { font-size: 12px; color: var(--text-2); margin-top: 2px; }
.toggle { width: 42px; height: 22px; background: var(--bg-4); border-radius: 99px; position: relative; cursor: pointer; transition: background var(--transition); flex-shrink: 0; }
.toggle.on { background: var(--accent); }
.toggle::after { content: ''; position: absolute; left: 3px; top: 3px; width: 16px; height: 16px; background: #fff; border-radius: 50%; transition: left var(--transition); box-shadow: 0 1px 4px rgba(0,0,0,.3); }
.toggle.on::after { left: 23px; }
.theme-picker { display: flex; gap: 10px; flex-wrap: wrap; }
.theme-swatch { width: 36px; height: 36px; border-radius: 50%; cursor: pointer; transition: transform .2s, box-shadow .2s; border: 3px solid transparent; }
.theme-swatch:hover { transform: scale(1.1); }
.theme-swatch.selected { border-color: var(--text-1); box-shadow: 0 0 0 3px var(--bg-2); }
.empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 20px; color: var(--text-3); min-height: 200px; }
.empty-state i { font-size: 48px; margin-bottom: 16px; }
.empty-state h3 { font-size: 15px; font-weight: 600; color: var(--text-2); margin-bottom: 6px; }
.empty-state p  { font-size: 13px; margin-bottom: 16px; }
.toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9000; display: flex; flex-direction: column; gap: 8px; }
.toast { display: flex; align-items: center; gap: 10px; background: var(--bg-2); border: 1px solid var(--border); border-left: 4px solid var(--accent); border-radius: var(--radius-sm); padding: 12px 16px; min-width: 260px; box-shadow: var(--shadow); font-size: 13px; font-weight: 500; animation: slideIn .3s cubic-bezier(.34,1.56,.64,1); }
@keyframes slideIn { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: none; } }
.toast.success { border-left-color: #10b981; }
.toast.error   { border-left-color: #ef4444; }
.toast i { font-size: 15px; }
.toast.success i { color: #10b981; }
.toast.error   i { color: #ef4444; }
.toast.info    i { color: var(--accent); }
.text-muted  { color: var(--text-2); }
.text-accent { color: var(--accent); }
.fw-600 { font-weight: 600; }
.fw-700 { font-weight: 700; }
.gap-8  { gap: 8px; }
.mt-4   { margin-top: 4px; }
.flex   { display: flex; }
.items-center { align-items: center; }
[data-tip] { position: relative; }
[data-tip]:hover::after { content: attr(data-tip); position: absolute; left: 50%; top: calc(100% + 6px); transform: translateX(-50%); background: var(--bg-4); color: var(--text-1); font-size: 11px; padding: 4px 8px; border-radius: 5px; white-space: nowrap; pointer-events: none; z-index: 99; }
@media (max-width: 768px) { body { overflow-x: hidden; } .sidebar { transform: translateX(-100%); } .sidebar.mobile-open { transform: none; width: 260px; } .main-wrap { margin-left: 0 !important; min-width: 0; max-width: 100vw; overflow-x: hidden; } .topbar { padding: 0 16px; } .topbar-title { font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; } .topbar-title small { display: none; } .page-content { padding: 16px; max-width: 100%; overflow-x: hidden; } .form-grid-2 { grid-template-columns: 1fr; } .welcome-icon { display: none; } .dash-grid { grid-template-columns: 1fr; } .table-wrap { overflow-x: auto; max-width: 100%; } .stats-grid { grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; } .stat-card { padding: 14px; gap: 10px; } .stat-icon { width: 38px; height: 38px; font-size: 16px; } .stat-data .val { font-size: 20px; } .stat-data .lbl { font-size: 11px; } .stat-data .chg { font-size: 10px; } }
</style>
</head>
<body>

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
      <a href="{{ route('dashboard') }}" class="nav-item {{ $page === 'dashboard' ? 'active' : '' }}">
        <i class="fa-solid fa-chart-pie"></i>
        <span class="nav-label">Dashboard</span>
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-group-label">Manajemen Film</div>
      <a href="{{ route('films.index') }}" class="nav-item {{ $page === 'films' ? 'active' : '' }}">
        <i class="fa-solid fa-clapperboard"></i>
        <span class="nav-label">Kelola Film</span>
      </a>
      <a href="{{ route('cast-members.index') }}" class="nav-item {{ $page === 'cast-members' ? 'active' : '' }}">
        <i class="fa-solid fa-masks-theater"></i>
        <span class="nav-label">Kelola Pemeran</span>
      </a>
      <a href="{{ route('crews.index') }}" class="nav-item {{ $page === 'crews' ? 'active' : '' }}">
        <i class="fa-solid fa-people-group"></i>
        <span class="nav-label">Kelola Crew</span>
      </a>
      <a href="{{ route('properties.index') }}" class="nav-item {{ $page === 'properties' ? 'active' : '' }}">
        <i class="fa-solid fa-boxes-stacked"></i>
        <span class="nav-label">Kebutuhan Properti</span>
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-group-label">Perencanaan</div>
      <a href="{{ route('rab-items.index') }}" class="nav-item {{ $page === 'rab-items' ? 'active' : '' }}">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <span class="nav-label">Kelola RAB</span>
      </a>
      <a href="{{ route('locations.index') }}" class="nav-item {{ $page === 'locations' ? 'active' : '' }}">
        <i class="fa-solid fa-map-location-dot"></i>
        <span class="nav-label">Kelola Lokasi</span>
      </a>
      <a href="{{ route('schedules.index') }}" class="nav-item {{ $page === 'schedules' ? 'active' : '' }}">
        <i class="fa-solid fa-calendar-days"></i>
        <span class="nav-label">Jadwal Produksi</span>
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-group-label">Kreatif</div>
      <a href="{{ route('scripts.index') }}" class="nav-item {{ $page === 'scripts' ? 'active' : '' }}">
        <i class="fa-solid fa-scroll"></i>
        <span class="nav-label">Kelola Skenario</span>
      </a>
      <a href="{{ route('shot-lists.index') }}" class="nav-item {{ $page === 'shot-lists' ? 'active' : '' }}">
        <i class="fa-solid fa-list-check"></i>
        <span class="nav-label">Kelola Shot List</span>
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-group-label">Sistem</div>
      <a href="{{ route('settings.index') }}" class="nav-item {{ $page === 'settings' ? 'active' : '' }}">
        <i class="fa-solid fa-gear"></i>
        <span class="nav-label">Pengaturan</span>
      </a>
    </div>
  </nav>
  <div class="sidebar-footer">
    <div class="avatar">{{ substr(auth()->user()?->name ?? 'AP', 0, 2) }}</div>
    <div class="user-info">
      <div class="user-name">{{ auth()->user()?->name ?? 'Admin Panel' }}</div>
      <div class="user-role">Super Admin</div>
    </div>
  </div>
</aside>

<div class="main-wrap" id="mainWrap">
  <header class="topbar">
    <button class="icon-btn" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div class="topbar-title" id="topbarTitle">
      @yield('page_title', 'Dashboard') <small>@yield('page_subtitle', '/ Overview')</small>
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
      <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button class="icon-btn" data-tip="Keluar" style="color:var(--text-3)">
          <i class="fa-solid fa-right-from-bracket"></i>
        </button>
      </form>
    </div>
  </header>
  <main class="page-content">
    @yield('content')
  </main>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
let isDark = document.documentElement.getAttribute('data-theme') === 'dark';
let sidebarCollapsed = false;

function toggleSidebar() {
  const sb = document.getElementById('sidebar'), mw = document.getElementById('mainWrap'), icon = document.getElementById('toggleIcon');
  if (window.innerWidth <= 768) { sb.classList.toggle('mobile-open'); return; }
  sidebarCollapsed = !sidebarCollapsed;
  sb.classList.toggle('collapsed', sidebarCollapsed);
  mw.classList.toggle('expanded', sidebarCollapsed);
  icon.className = sidebarCollapsed ? 'fa-solid fa-angles-right' : 'fa-solid fa-angles-left';
}

function toggleTheme() {
  isDark = !isDark;
  document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
  const icon = document.getElementById('themeIcon');
  if (icon) icon.className = isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
  const dt = document.getElementById('darkToggle');
  if (dt) dt.classList.toggle('on', isDark);
}

function toggleThemeFromSettings(el) {
  el.classList.toggle('on');
  isDark = el.classList.contains('on');
  document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
  const icon = document.getElementById('themeIcon');
  if (icon) icon.className = isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
}

function openModal(id) { document.getElementById(id).classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) { if (e.target === this) { this.classList.remove('open'); document.body.style.overflow = ''; } });
});

function showToast(msg, type = 'info') {
  const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', info: 'fa-circle-info' };
  const toast = document.createElement('div');
  toast.className = 'toast ' + type;
  toast.innerHTML = '<i class="fa-solid ' + (icons[type] || icons.info) + '"></i> ' + msg;
  document.getElementById('toastContainer').appendChild(toast);
  setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(40px)'; toast.style.transition = '.3s'; }, 2800);
  setTimeout(() => toast.remove(), 3200);
}

function filterTable(input, tableId) {
  const q = input.value.toLowerCase();
  document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => { row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none'; });
}

function filterByStatus(select, tableId) {
  const q = select.value.toLowerCase();
  document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => { row.style.display = (!q || row.textContent.toLowerCase().includes(q)) ? '' : 'none'; });
}

function switchSettings(el, panelId) {
  document.querySelectorAll('.settings-menu-item').forEach(i => i.classList.remove('active'));
  document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.getElementById(panelId).classList.add('active');
}

function selectSwatch(el) {
  document.querySelectorAll('.theme-swatch').forEach(s => s.classList.remove('selected'));
  el.classList.add('selected');
  const color = el.style.background;
  document.documentElement.style.setProperty('--accent', color);
  document.documentElement.style.setProperty('--accent-hover', color);
  showToast('Warna aksen diperbarui!', 'success');
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { document.querySelectorAll('.modal-overlay.open').forEach(m => { m.classList.remove('open'); document.body.style.overflow = ''; }); }
});

@stack('scripts')
</script>
</body>
</html>
