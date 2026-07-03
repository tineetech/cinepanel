<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Pemeran</title>
<style>
  body { font-family: sans-serif; font-size: 12px; }
  h1 { font-size: 18px; margin-bottom: 5px; }
  .sub { color: #666; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; }
  th { background: #f97316; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
  td { padding: 6px 10px; border-bottom: 1px solid #ddd; }
  tr:nth-child(even) td { background: #f9f9f9; }
  .text-muted { color: #999; }
</style>
</head>
<body>
<h1>Laporan Data Pemeran</h1>
<div class="sub">CinePanel — Manajemen Produksi Film</div>
<table>
<thead>
  <tr>
    <th>#</th><th>Nama</th><th>Karakter</th><th>Film</th><th>Tipe Peran</th><th>Usia</th><th>Telepon</th><th>Status</th>
  </tr>
</thead>
<tbody>
  @forelse ($castMembers as $c)
  <tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $c->name }}</td>
    <td>{{ $c->character_name ?? '-' }}</td>
    <td>{{ $c->film?->title ?? '-' }}</td>
    <td>{{ $c->role_type ?? '-' }}</td>
    <td>{{ $c->age ?? '-' }}</td>
    <td>{{ $c->phone ?? '-' }}</td>
    <td>{{ $c->status ?? '-' }}</td>
  </tr>
  @empty
  <tr><td colspan="8" style="text-align:center;color:#999">Tidak ada data pemeran</td></tr>
  @endforelse
</tbody>
</table>
</body>
</html>
