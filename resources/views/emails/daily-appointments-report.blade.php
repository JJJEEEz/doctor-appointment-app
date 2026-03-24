<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte diario de citas</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2>
        Reporte diario de citas - {{ $reportDate->format('d/m/Y') }}
        @if($doctor)
            (Dr. {{ $doctor->user?->name ?? 'N/D' }})
        @endif
    </h2>

    @if($appointments->isEmpty())
        <p>No hay citas programadas para hoy.</p>
    @else
        <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%;">
            <thead style="background: #f3f4f6;">
            <tr>
                <th align="left">Hora</th>
                <th align="left">Paciente</th>
                @if(! $doctor)
                    <th align="left">Doctor</th>
                @endif
                <th align="left">Estado</th>
                <th align="left">Motivo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                    <td>{{ $appointment->patient?->name ?? 'N/D' }}</td>
                    @if(! $doctor)
                        <td>{{ $appointment->doctor?->user?->name ?? 'N/D' }}</td>
                    @endif
                    <td>{{ $appointment->statusLabel() }}</td>
                    <td>{{ $appointment->reason ?: '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
