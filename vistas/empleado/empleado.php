<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'empleado') {
    header('Location: vistas/login.php');
    exit;
}

require_once __DIR__ . '/../../controladores/PedidoController.php';
$controller = new PedidoController();
$pedidos = $controller->listarConDetalles();

// Reordenar: primero no entregados, luego entregados
usort($pedidos, function($a, $b) {
    if ($a['estado'] === 'entregado' && $b['estado'] !== 'entregado') return 1;
    if ($a['estado'] !== 'entregado' && $b['estado'] === 'entregado') return -1;
    return 0;
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pedidos - Empleado</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
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
            background-color: #ecf0f1;
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
            background-color: #2980b9;
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
            background-color: #1f5980;
        }

        .navbar .activo {
            background-color: #1a5276;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2 {
            margin-bottom: 18px;
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
        }

        .scroll-tabla {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        th {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .estado-form {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        select, button {
            padding: 6px 10px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .estado-pendiente {
            color: #d35400;
            font-weight: bold;
        }

        .estado-preparado {
            color: #2980b9;
            font-weight: bold;
        }

        .estado-listo {
            color: #8e44ad;
            font-weight: bold;
        }

        .estado-entregado {
            color: #27ae60;
            font-weight: bold;
        }

        .texto-entregado {
            color: #27ae60;
            font-weight: 600;
        }
    </style>
</head>
<body>

<header>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="margin: 0;">Panel del Empleado - Gestión de Pedidos</h1>
        <form method="post" action="../logout.php">
            <button type="submit" style="background-color: #e74c3c; color: white; padding: 8px 16px; font-size: 14px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; display: flex; align-items: center;">
                <img src="https://cdn-icons-png.freepik.com/512/16587/16587480.png" alt="Salir" style="width: 20px; height: 20px; margin-right: 6px;">
                Cerrar sesión
            </button>
        </form>
    </div>
</header>

<div class="navbar">
    <a href="stock_bajo.php">Información de stock</a>
    <a href="agregar_libros.php">Gestionar Libros</a>
    <a href="empleado.php" class="activo">Lista de Pedidos</a>
    <a href="ventas.php">Ventas</a>
</div>

<div class="container">
    <h2>Listado de Pedidos</h2>
    <div class="scroll-tabla">
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Libros</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['cliente']) ?></td>
                        <td><?= htmlspecialchars($p['libros']) ?></td>
                        <td class="estado-<?= strtolower($p['estado']) ?>"><?= ucfirst($p['estado']) ?></td>
                        <td>$<?= number_format($p['total'], 2) ?></td>
                        <td><?= $p['fecha_pedido'] ?></td>
                        <td>
                            <?php if ($p['estado'] !== 'entregado'): ?>
                                <form method="post" action="cambiar_estado.php" class="estado-form">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <select name="nuevo_estado">
                                        <option value="pendiente" <?= $p['estado'] === 'pendiente' ? 'selected' : '' ?>>pendiente</option>
                                        <option value="preparado" <?= $p['estado'] === 'preparado' ? 'selected' : '' ?>>preparado</option>
                                        <option value="listo" <?= $p['estado'] === 'listo' ? 'selected' : '' ?>>listo</option>
                                        <option value="entregado" <?= $p['estado'] === 'entregado' ? 'selected' : '' ?>>entregado</option>
                                    </select>
                                    <button type="submit">Actualizar</button>
                                </form>
                            <?php else: ?>
                                <span class="texto-entregado">Producto entregado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
