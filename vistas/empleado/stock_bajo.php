<?php
session_start();
require_once __DIR__ . '/../../controladores/ProductoController.php';

$controller = new ProductoController();
$productosStockBajo = $controller->obtenerStockBajo();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alertas de Stock Bajo</title>
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

        .navbar .activo {
            background-color: #1c5980;
        }

        .panel {
            max-width: 1000px;
            margin: 30px auto;
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .titulo-seccion {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 18px;
        }

        .alerta {
            font-size: 18px;
            font-weight: 500;
            color: #666;
            text-align: center;
            padding: 20px;
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
            text-align: left;
        }

        tbody tr:hover {
            background-color: #f0f8ff;
            transition: background-color 0.3s ease;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            th {
                text-align: right;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
                color: #555;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Alertas de Stock Bajo</h1>
</header>

<div class="navbar">
    <a href="stock_bajo.php" class="activo">Información de stock</a>
    <a href="agregar_libros.php">Gestionar Libros</a>
    <a href="empleado.php">Lista de Pedidos</a>
    <a href="ventas.php">Ventas</a>
</div>

<div class="panel">
    <h2 class="titulo-seccion">Productos con stock por debajo del mínimo</h2>

    <?php if ($productosStockBajo && $productosStockBajo->rowCount() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Autor</th>
                    <th>Stock</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productosStockBajo as $producto): ?>
                    <tr>
                        <td data-label="ID"><?= $producto['id'] ?></td>
                        <td data-label="Nombre"><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td data-label="Autor"><?= htmlspecialchars($producto['autor']) ?></td>
                        <td data-label="Stock"><?= $producto['stock'] ?></td>
                        <td data-label="Precio">S/ <?= number_format($producto['precio'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alerta">✅ Todos los productos tienen suficiente stock.</p>
    <?php endif; ?>
</div>

</body>
</html>
