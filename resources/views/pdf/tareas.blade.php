<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-wrapper {
            border: 1px solid #000;
            padding: 10px;
        }

        .empresa {
            font-weight: bold;
            text-align: left;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .filters {
            width: 100%;
            border-collapse: collapse;
        }


        .filters th {
            background-color: rgb(0, 93, 192);
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .filters td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            word-wrap: break-word;
        }

        .logo-img {
            max-height: 90px;
            width: auto;
            display: block;
            margin: 0 auto;
            padding-bottom: 5px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }

        .task-table th {
            background-color: rgb(0, 93, 192);
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px;
            text-align: center;


        }

        .task-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            word-wrap: break-word;
        }


        .title-box {
            border: 1px solid black;
            color: rgb(0, 93, 192);
            font-weight: bold;
            text-align: center;
            font-size: 16px;
            padding: 2px;
            margin: 5px auto 30px auto;
            width: 50%;

        }

        .project-row th {
            background-color: #e0e0e0;
            color: black;
            text-align: center;
            padding: 0px 8px 8px 8px;
            font-weight: bold;
            border: 1px solid #000;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .footer strong {
            vertical-align: middle;
        }

        .minutos-box {
            display: inline-block;
            border: 1px solid #000;
            padding: 2px 10px;
            font-weight: bold;
            font-size: 14px;
            vertical-align: middle;
            line-height: 1.2;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body>

    <!-- CABECERA CON LOGO, EMPRESA Y FILTROS -->
    <div class="header-wrapper">
        <table class="header-table">
            <tr>
                <td style="width: 30%; vertical-align: middle; height: 90px;">
                    <img src="{{ public_path('images/logo.png') }}" class="logo-img">
                </td>
                <td style="width: 70%;">
                    <div class="empresa">1 - SOLUCIONES INFORMÁTICAS MJ S.C.A</div>
                    <table class="filters">
                        <tr>
                            <th>DESDE FECHA:</th>
                            <td>{{ $desde }}</td>
                            <th>PROYECTO:</th>
                            <td>{{ $proyectoNombre ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>HASTA FECHA:</th>
                            <td>{{ $hasta }}</td>
                            <th>USUARIO:</th>
                            <td>{{ $usuarioNombre ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- TÍTULO -->
    <div class="title-box">
        INFORME DE TAREAS REALIZADAS
    </div>

    <!-- TABLA DE TAREAS -->
    <table class="task-table">
        <thead>
            <tr class="project-row">
                <th colspan="6">{{ $proyectoNombre ?? 'Todos los proyectos' }}</th>
            </tr>
            <tr>
                <th>ID</th>
                <th>INICIO</th>
                <th>FIN</th>
                <th>MIN.</th>
                <th>USUARIO</th>
                <th>TAREA REALIZADA</th>
            </tr>
        </thead>
        <tbody>
            @php $totalMin = 0; @endphp
            @foreach ($tareas as $tarea)
            @php $totalMin += $tarea->minutos; @endphp
            <tr>
                <td>{{ $tarea->id }}</td>
                <td>{{ \Carbon\Carbon::parse($tarea->inicio)->format('d/m/Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($tarea->fin)->format('d/m/Y H:i') }}</td>
                <td>{{ $tarea->minutos }}</td>
                <td>{{ $tarea->usuario->name }}</td>
                <td>{{ $tarea->descripcion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- PIE -->
    <div class="footer">
        <strong>TOTAL MINS:</strong>
        <span class="minutos-box">{{ $totalMin }}</span>
    </div>

</body>

</html>