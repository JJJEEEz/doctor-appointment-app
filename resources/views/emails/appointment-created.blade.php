<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de cita</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2>Comprobante de cita medica</h2>

    <p>Se ha registrado una nueva cita medica.</p>

    <ul>
        <li><strong>Paciente:</strong> {{ $appointment->patient?->name ?? 'N/D' }}</li>
        <li><strong>Doctor:</strong> {{ $appointment->doctor?->user?->name ?? 'N/D' }}</li>
        <li><strong>Especialidad:</strong> {{ $appointment->doctor?->speciality?->name ?? 'N/D' }}</li>
        <li><strong>Fecha:</strong> {{ optional($appointment->date)->format('d/m/Y') }}</li>
        <li><strong>Horario:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</li>
        <li><strong>Estado:</strong> {{ $appointment->statusLabel() }}</li>
    </ul>

    <p>Adjunto encontrara el comprobante en PDF.</p>
</body>
</html>
