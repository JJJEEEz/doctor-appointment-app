<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de cita</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 14px;
        }

        .container {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
        }

        .title {
            margin: 0 0 16px;
            color: #111827;
            font-size: 20px;
        }

        .row {
            margin: 8px 0;
        }

        .label {
            display: inline-block;
            min-width: 120px;
            font-weight: bold;
        }

        .footer {
            margin-top: 24px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="title">Comprobante de cita medica</h1>

    <div class="row"><span class="label">Paciente:</span> {{ $appointment->patient?->name ?? 'N/D' }}</div>
    <div class="row"><span class="label">Correo paciente:</span> {{ $appointment->patient?->email ?? 'N/D' }}</div>
    <div class="row"><span class="label">Doctor:</span> {{ $appointment->doctor?->user?->name ?? 'N/D' }}</div>
    <div class="row"><span class="label">Especialidad:</span> {{ $appointment->doctor?->speciality?->name ?? 'N/D' }}</div>
    <div class="row"><span class="label">Fecha:</span> {{ optional($appointment->date)->format('d/m/Y') }}</div>
    <div class="row"><span class="label">Horario:</span> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</div>
    <div class="row"><span class="label">Estado:</span> {{ $appointment->statusLabel() }}</div>
    <div class="row"><span class="label">Motivo:</span> {{ $appointment->reason ?: '-' }}</div>

    <div class="footer">
        Generado automaticamente por el sistema de citas medicas.
    </div>
</div>
</body>
</html>
