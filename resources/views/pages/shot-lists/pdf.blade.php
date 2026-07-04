<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Shot List</title>
<style>
  body { font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #000; margin: 0; padding: 20px; }
  h1 { font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 2px; color: #000; }
  .sub { font-size: 10px; color: #333; margin-bottom: 16px; border-bottom: 2px solid #000; padding-bottom: 8px; }
  table { width: 100%; border-collapse: collapse; border: 1px solid #000; }
  th { background: #000; color: #fff; padding: 10px 12px; text-align: left; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; border: 1px solid #000; }
  td { padding: 8px 12px; border: 1px solid #999; color: #000; vertical-align: top; }
  tr:nth-child(even) td { background: #f5f5f5; }
  tr:nth-child(odd) td { background: #fff; }
</style>
</head>
<body>
<h1>LAPORAN SHOT LIST FILM {{ $focusFilm ? strtoupper($focusFilm->title) : '' }}</h1>
<div class="sub">CinePanel — Manajemen Produksi Film</div>
<table>
<thead>
  <tr>
    <th style="width:60px">Scene</th>
    <th style="width:40px">Shot</th>
    <th style="width:80px">Tipe Kamera</th>
    <th style="width:70px">Gerakan</th>
    <th style="width:55px">Durasi</th>
    <th style="width:70px">Lokasi</th>
    <th style="width:auto">Deskripsi Shot</th>
  </tr>
</thead>
<tbody>
  @forelse ($shotLists as $s)
  <tr>
    <td>{{ $s->scene ?? '-' }}</td>
    <td>{{ preg_replace('/\D/', '', $s->scene) }}{{ chr(64 + $loop->iteration) }}</td>
    <td>{{ $s->camera_type ?? '-' }}</td>
    <td>{{ $s->camera_movement ?? '-' }}</td>
    <td>{{ $s->estimated_duration ?? '-' }}</td>
    <td>{{ $s->location?->name ?? '-' }}</td>
    <td>{{ $s->shot_description ?? '-' }}</td>
  </tr>
  @empty
  <tr><td colspan="7" style="text-align:center;color:#666;padding:20px">Tidak ada data shot list</td></tr>
  @endforelse
</tbody>
</table>
</body>
</html>
