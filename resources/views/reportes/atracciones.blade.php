<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de atracciones</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 20px; margin: 0 0 4px 0; }
        .meta { color: #6b7280; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4f46e5; color: #ffffff; text-align: left; padding: 8px; }
        td { padding: 7px 8px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background: #f9fafb; }
        .total { margin-top: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Reporte de atracciones</h1>
    <div class="meta">
        Generado el {{ $fecha }} por {{ $generadoPor }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Sede</th>
                <th>Atracción</th>
                <th>Edad mínima</th>
                <th>Estatura mínima (m)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($atracciones as $atraccion)
                <tr>
                    <td>{{ $atraccion->sede->nombre }}</td>
                    <td>{{ $atraccion->nombre }}</td>
                    <td>{{ $atraccion->edad_minima }} años</td>
                    <td>{{ $atraccion->estatura_minima }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No hay atracciones registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="total">Total de atracciones: {{ $atracciones->count() }}</div>
</body>
</html>
