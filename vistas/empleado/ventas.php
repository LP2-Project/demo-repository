<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'empleado') {
    header('Location: vistas/login.php');
    exit;
}

require_once __DIR__ . '/../../controladores/PedidoController.php';
$controller = new PedidoController();
$ventas = array_filter($controller->listarConDetalles(), fn($p) => $p['estado'] === 'entregado');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas - Empleado</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4faff;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            padding: 16px;
            color: white;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .navbar {
            background-color: #eaf3fa;
            display: flex;
            justify-content: flex-end;
            padding: 10px 30px;
            gap: 10px;
            align-items: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        .navbar a,
        .navbar form button {
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .navbar a:hover,
        .navbar form button:hover {
            background-color: #2a7ab7;
        }

        .navbar img {
            width: 18px;
            height: 18px;
            margin-right: 6px;
        }

        .navbar .activo {
            background-color: #1c5980;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        th {
            background-color: #3498db;
            color: white;
            padding: 14px 16px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e0ecf8;
            color: #333;
        }

        tbody tr:hover {
            background-color: #f0f8ff;
            transition: background-color 0.3s ease;
        }

        .scroll-tabla {
            max-height: 500px;
            overflow-y: auto;
            margin-top: 20px;
            border-radius: 10px;
        }

        .vacío {
            text-align: center;
            color: #888;
            margin: 30px;
            font-style: italic;
        }

        .grafico-container {
            max-width: 1000px;
            margin: 40px auto;
            background-color: #ffffff;
            border: 2px solid #cce5ff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        .grafico-container h2 {
            color: #2574a9;
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<header>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Panel del Empleado - Ventas</h1>
        <form method="post" action="../logout.php" style="margin: 0;">
    <button type="submit" style="background-color: #e74c3c; color: white; padding: 8px 14px; font-size: 14px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; display: flex; align-items: center;">
        <img src="https://cdn-icons-png.freepik.com/512/16587/16587480.png" alt="Salir" style="width: 18px; height: 18px; margin-right: 6px;">
        Cerrar sesión
    </button>
</form>

    </div>
</header>

<div class="navbar">
    <a href="stock_bajo.php">Informacion de stock</a>
    <a href="agregar_libros.php">Gestionar Libros</a>
    <a href="empleado.php" class="activo">Lista de Pedidos</a>
    <a href="ventas.php">Ventas</a>
</div>

<div class="container">
    <h2>Listado de Ventas (Entregados)</h2>
    <?php if (empty($ventas)): ?>
        <p class="vacío">No se han registrado ventas aún.</p>
    <?php else: ?>
        <div class="scroll-tabla">
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Libros</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['cliente']) ?></td>
                            <td><?= htmlspecialchars($venta['libros']) ?></td>
                            <td>$<?= number_format($venta['total'], 2) ?></td>
                            <td><?= htmlspecialchars($venta['fecha_pedido']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
// Datos para el gráfico de ingresos
$ingresos = [];
foreach ($ventas as $venta) {
    $fecha = date('Y-m-d', strtotime($venta['fecha_pedido']));
    $ingresos[$fecha] = ($ingresos[$fecha] ?? 0) + $venta['total'];
}
$fechas = array_keys($ingresos);
$totales = array_values($ingresos);

// Datos para el gráfico de libros
$librosVendidos = [];
$mesActual = date('Y-m');
foreach ($ventas as $venta) {
    if (date('Y-m', strtotime($venta['fecha_pedido'])) === $mesActual) {
        $libros = explode(',', $venta['libros']);
        foreach ($libros as $libro) {
            $libro = trim($libro);
            $librosVendidos[$libro] = ($librosVendidos[$libro] ?? 0) + 1;
        }
    }
}
$librosLabels = array_keys($librosVendidos);
$librosCount = array_values($librosVendidos);
?>

<div class="grafico-container">
    <h2>Ingresos diarios del mes</h2>
    <canvas id="graficoIngresos"></canvas>
</div>

<div class="grafico-container">
    <h2>Libros más vendidos del mes</h2>
    <canvas id="graficoLibrosVendidos"></canvas>
</div>

<script>
const ctxIngresos = document.getElementById('graficoIngresos').getContext('2d');
new Chart(ctxIngresos, {
    type: 'line',
    data: {
        labels: <?= json_encode($fechas) ?>,
        datasets: [{
            label: '🪙 Ingresos (USD)',
            data: <?= json_encode($totales) ?>,
            fill: true,
            backgroundColor: 'rgba(74, 144, 226, 0.3)',
            borderColor: '#1c6fa5',
            tension: 0.4,
            pointBackgroundColor: '#1c6fa5',
            borderWidth: 3,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    color: '#2c3e50',
                    font: { size: 14, weight: 'bold' }
                }
            }
        },
        scales: {
            x: {
                ticks: { color: '#333' },
                grid: { color: '#e3f2fd' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#333' },
                grid: { color: '#e3f2fd' }
            }
        }
    }
});

const ctxLibros = document.getElementById('graficoLibrosVendidos').getContext('2d');
new Chart(ctxLibros, {
    type: 'bar',
    data: {
        labels: <?= json_encode($librosLabels) ?>,
        datasets: [{
            label: 'Unidades vendidas',
            data: <?= json_encode($librosCount) ?>,
            backgroundColor: '#4a90e2',
            borderRadius: 6,
            barThickness: 22
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            legend: {
                labels: {
                    color: '#1a5276',
                    font: { size: 14, weight: 'bold' }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { color: '#333' },
                grid: { color: '#eef7fd' }
            },
            y: {
                ticks: { color: '#333' },
                grid: { color: '#eef7fd' }
            }
        }
    }
});
</script>

</body>
</html>
