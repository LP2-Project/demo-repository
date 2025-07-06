<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header('Location: ../vistas/login.php');
    exit;
}

require_once __DIR__ . '/../../controladores/PedidoController.php';

$pedidoController = new PedidoController();
$usuario_id = $_SESSION['id_usuario'];

// Eliminar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $pedido_id = (int)$_POST['eliminar_id'];
    $detalles = $pedidoController->obtenerDetallePedido($pedido_id);
    $primerLibro = $detalles[0]['titulo'] ?? '';

    $exito = $pedidoController->eliminarPorUsuario($pedido_id, $usuario_id);
    $_SESSION['mensaje'] = $exito
        ? "El pedido del libro " . htmlspecialchars($primerLibro ?: 'libro') . " fue eliminado correctamente."
        : "No se pudo eliminar el pedido.";
    header('Location: orders.php');
    exit;
}

$pedidos = $pedidoController->obtenerPedidosPorUsuario($usuario_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
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

        .mensaje {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
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

        .btn-eliminar {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-eliminar:hover {
            background-color: #c0392b;
        }

        .estado {
            font-weight: bold;
            padding: 6px 10px;
            border-radius: 6px;
            display: inline-block;
        }

        .estado.pendiente {
            background-color: #f1c40f;
            color: #000;
        }

        .estado.preparado {
            background-color: #3498db;
            color: #fff;
        }

        .estado.listo {
            background-color: #2ecc71;
            color: #fff;
        }

        .estado.entregado {
            background-color: #95a5a6;
            color: white;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="cliente.php" class="inicio-link">← Volver al inicio</a>
    Panel del Usuario - Pedidos
</div>

<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="mensaje"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="panel">
    <h2>📚 Lista de Pedidos</h2>

    <?php if (!empty($pedidos)): ?>
        <table>
            <tr>
                <th>Libros</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($pedidos as $pedido): ?>
                <?php
                    $detalles = $pedidoController->obtenerDetallePedido($pedido['id']);
                    $primerLibro = $detalles[0]['titulo'] ?? 'libro';
                    $estado = strtolower($pedido['estado']);
                ?>
                <tr>
                    <td>
                        <?php foreach ($detalles as $item): ?>
                             <?= htmlspecialchars($item['titulo']) ?><br>
                        <?php endforeach; ?>
                    </td>
                    <td>US$ <?= number_format($pedido['total'], 2) ?></td>
                    <td><span class="estado <?= $estado ?>"><?= ucfirst($estado) ?></span></td>
                    <td><?= htmlspecialchars($pedido['fecha_pedido']) ?></td>
                    <td>
                        <?php if ($estado !== 'entregado'): ?>
                            <form method="post" action="orders.php" style="display:inline;">
                                <input type="hidden" name="eliminar_id" value="<?= $pedido['id'] ?>">
                                <button type="submit" class="btn-eliminar"
                                    onclick="return confirm('¿Estás seguro de anular el pedido del libro <?= addslashes($primerLibro) ?>?')">
                                    Anular
                                </button>
                            </form>
                        <?php else: ?>
                            <span style="color: #2ecc71; font-weight: bold;">Entregado</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No has realizado ningún pedido aún.</p>
    <?php endif; ?>
</div>

</body>
</html>
