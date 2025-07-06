<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header('Location: ../vistas/login.php');
    exit;
}

require_once __DIR__ . '/../../controladores/PedidoController.php';

$pedidoController = new PedidoController();
$usuario_id = $_SESSION['id_usuario'];

$pedido = $pedidoController->obtenerPedidoPendiente($usuario_id);
$items = [];

if ($pedido) {
    $pedido_id = $pedido['id'];
    $items = $pedidoController->obtenerDetallePedido($pedido_id);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e8ecf0;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
            position: relative;
        }

        .header .inicio-link {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #ecf0f1;
            text-decoration: none;
            background-color: #2980b9;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .header .inicio-link:hover {
            background-color: #1c5980;
        }

        .panel {
            max-width: 900px;
            margin: 30px auto;
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .total {
            font-weight: bold;
            color: #2c3e50;
            font-size: 16px;
        }

        .btn-confirmar {
            display: block;
            margin: 30px auto 0;
            background-color: #27ae60;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-confirmar:hover {
            background-color: #219150;
        }

        .mensaje-vacio {
            text-align: center;
            color: #7f8c8d;
            font-size: 18px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="cliente.php" class="inicio-link">← Volver al inicio</a>
    🛒 Mi Carrito de Compras
</div>

<div class="panel">
    <h2>Libros seleccionados</h2>

    <?php if ($pedido): ?>
        <p><strong>Pedido #<?= $pedido['id'] ?></strong> | Estado: <em><?= ucfirst($pedido['estado']) ?></em></p>

        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Precio (US$)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['titulo']) ?></td>
                        <td><?= number_format($item['precio'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="total">Total</td>
                    <td class="total">US$ <?= number_format($pedido['total'], 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <form method="post" action="confirmar_pedido.php">
            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
            <button class="btn-confirmar" type="submit">Confirmar pedido (Pago contra entrega)</button>
        </form>
    <?php else: ?>
        <p class="mensaje-vacio">No tienes libros en tu carrito actualmente.</p>
    <?php endif; ?>
</div>

</body>
</html>
