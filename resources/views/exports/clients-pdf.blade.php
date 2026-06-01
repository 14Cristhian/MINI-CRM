<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  body {
    font-family: "DejaVu Sans", sans-serif;
    font-size: 11px;
    color: #1f2937;
    margin: 0;
    padding: 24px 28px 40px;
  }

  .overline {
    font-size: 9px;
    font-weight: bold;
    color: #6366f1;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin: 0 0 5px;
  }
  h1 {
    font-size: 19px;
    font-weight: bold;
    color: #111827;
    margin: 0 0 5px;
  }
  .meta {
    font-size: 9px;
    color: #6b7280;
    margin: 0;
    line-height: 1.6;
  }
  .divider {
    border: none;
    border-top: 3px solid #4f46e5;
    margin: 13px 0 16px;
  }

  /* ── Summary pill ── */
  .summary {
    font-size: 9.5px;
    color: #374151;
    background-color: #f5f3ff;
    border-left: 3px solid #4f46e5;
    padding: 8px 14px;
    margin-bottom: 16px;
  }
  .summary strong { color: #4f46e5; }

  /* ── Data table ── */
  .dt { width: 100%; border-collapse: collapse; }

  .dt thead th {
    background-color: #4f46e5;
    color: #ffffff;
    padding: 8px 10px;
    font-size: 7.5px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    text-align: left;
  }

  .dt tbody td {
    padding: 8px 10px;
    font-size: 9.5px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
    line-height: 1.5;
  }

  .dt tbody tr.even td { background-color: #fafafa; }

  /* ── Status badges ── */
  .badge {
    font-size: 7.5px;
    font-weight: bold;
    padding: 2px 7px;
    border-radius: 12px;
  }
  .badge-activo    { background-color: #dcfce7; color: #166534; }
  .badge-inactivo  { background-color: #f1f5f9; color: #64748b; }
  .badge-prospecto { background-color: #fef9c3; color: #854d0e; }

  /* ── Footer ── */
  .footer-table { width: 100%; border-collapse: collapse; margin-top: 18px; border-top: 1px solid #e5e7eb; }
  .footer-table td { padding: 6px 0; font-size: 8px; color: #9ca3af; }
</style>
</head>
<body>

<p class="overline">Mini CRM</p>
<h1>Listado de Clientes</h1>
<p class="meta">
  Generado: {{ $generatedAt->format('d/m/Y \a \l\a\s H:i') }}
  @if(!empty($filters['search'])) &nbsp;&middot;&nbsp; Búsqueda: <strong>&ldquo;{{ $filters['search'] }}&rdquo;</strong>@endif
  @if(!empty($filters['status'])) &nbsp;&middot;&nbsp; Estado: <strong>{{ ucfirst($filters['status']) }}</strong>@endif
</p>

<hr class="divider">

<p class="summary">
  Total exportado: <strong>{{ $clients->count() }} cliente{{ $clients->count() !== 1 ? 's' : '' }}</strong>
</p>

<table class="dt">
  <thead>
    <tr>
      <th style="width:26px">#</th>
      <th style="width:128px">Nombre</th>
      <th style="width:110px">Empresa</th>
      <th>Email</th>
      <th style="width:90px">Teléfono</th>
      <th style="width:70px">Estado</th>
      <th style="width:38px;text-align:center">Cont.</th>
      <th style="width:108px">Contacto Principal</th>
      <th style="width:64px">Registrado</th>
    </tr>
  </thead>
  <tbody>
    @forelse($clients as $client)
    <tr class="{{ $loop->even ? 'even' : '' }}">
      <td style="color:#9ca3af;font-size:9px">{{ $client->id }}</td>
      <td><strong>{{ $client->name }}</strong></td>
      <td>{{ $client->company ?? '—' }}</td>
      <td style="font-size:8.5px;color:#6b7280">{{ $client->email ?? '—' }}</td>
      <td>{{ $client->phone ?? '—' }}</td>
      <td>
        <span class="badge badge-{{ $client->status }}">{{ ucfirst($client->status) }}</span>
      </td>
      <td style="text-align:center;font-weight:700">{{ $client->contacts_count }}</td>
      <td>{{ $client->primaryContact?->name ?? '—' }}</td>
      <td style="color:#6b7280">{{ $client->created_at->format('d/m/Y') }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="9" style="text-align:center;padding:20px;color:#9ca3af">Sin registros para mostrar</td>
    </tr>
    @endforelse
  </tbody>
</table>

<table class="footer-table">
  <tr>
    <td>Mini CRM &nbsp;&middot;&nbsp;</td>
    <td style="text-align:right">Exportado {{ now()->format('d/m/Y') }}</td>
  </tr>
</table>

</body>
</html>
