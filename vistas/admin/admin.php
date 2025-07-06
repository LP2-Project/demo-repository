<?php
// archivo: admin.php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header('Location: ../vistas/login.php');
    exit;
}

require_once __DIR__ . '/../../controladores/UsuarioController.php';
require_once __DIR__ . '/../../controladores/PedidoController.php';

$usuarioController = new UsuarioController();
$pedidoController = new PedidoController();

$usuarios = $usuarioController->listar();
$pedidos = $pedidoController->listarConDetalles(); // este método lo agregamos abajo
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #34495e;
            padding: 16px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logout-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logout-btn img {
            width: 24px;
            height: 24px;
            background-color: white;
            border-radius: 50%;
            padding: 2px;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 30px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        .edit-btn, .delete-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            text-decoration: none;
        }
        .edit-btn {
            background-color: #f1c40f;
            color: white;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>
<header>
    <h1>Panel del Administrador</h1>
    <form method="post" action="../logout.php">
        <button type="submit" class="logout-btn">
            <img src="https://cdn-icons-png.flaticon.com/512/5565/5565704.png" alt="logout">
            Cerrar sesión
        </button>
    </form>
</header>

<div class="container">
    <h2>Gestión de Usuarios</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Tipo</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['correo']) ?></td>
                <td><?= ucfirst($u['tipo']) ?></td>
                <td><?= $u['fecha_registro'] ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="edit-btn">Editar</a>
                    <a href="eliminar_usuario.php?id=<?= $u['id'] ?>" class="delete-btn"
                       onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Historial de Pedidos</h2>
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
                <td><?= ucfirst($p['estado']) ?></td>
                <td>$<?= number_format($p['total'], 2) ?></td>
                <td><?= $p['fecha_pedido'] ?></td>
                <td>
                    <a href="eliminar_pedido.php?id=<?= $p['id'] ?>" class="delete-btn"
                       onclick="return confirm('¿Deseas eliminar este pedido?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
