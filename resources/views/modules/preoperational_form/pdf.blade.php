<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
      margin: 0 20px;
    }

    header {
      margin-bottom: 20px;
    }

    /* Fila superior: logo a la izquierda, info a la derecha */
    header .top-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      margin-bottom: 10px;
    }

    header .top-row .logo img {
      height: 50px;
    }

    header .top-row .info {
      text-align: right;
      font-size: 10px;
      color: #555;
    }

    header .top-row .info .timestamp,
    header .top-row .info .user {
      display: block;
    }

    /* Título centrado debajo */
    header .title {
      text-align: center;
      font-size: 16px;
      font-weight: bold;
      width: 100%;
      margin-top: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    th, td {
      border: 1px solid #444;
      padding: 6px;
    }

    th {
      background: #1e73be;
      color: #fff;
    }

    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: right;
      font-size: 10px;
      color: #888;
    }
  </style>
</head>
<body>
  <header>
    <div class="top-row">
      <div class="logo">
        <img src="{{ public_path('assets/HolaEpc.png') }}" alt="Logo Epc">
      </div>
      <div class="info">
        <div class="timestamp">Exportado: {{ $now->format('d/m/Y H:i:s') }}</div>
        <div class="user">Usuario: {{ $user->fullname }}</div>
      </div>
    </div>
    <div class="title">Sistema de gestión vehicular - Epc</div>
  </header>
  
  <table>
    <thead>
      <tr>
        <th>Fecha</th>
        <th>Conductor</th>
        <th>Vehículo</th>
        <th>Observaciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($records as $r)
        <tr>
          <td>{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
          <td>{{ $r->driver->name }}</td>
          <td>{{ $r->vehicle->plate }}</td>
          <td>{{ $r->observations }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4" style="text-align:center">No hay registros</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div>Total de registros: {{ $records->count() }}</div>

  <div class="footer">
    Página <script>document.write(this.page);</script> de <script>document.write(this.pages);</script>
  </div>
</body>
</html>
