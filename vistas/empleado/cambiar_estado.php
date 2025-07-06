<?php
// archivo: cambiar_estado.php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'empleado') {
    header('Location: vistas/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['id'] ?? null;
    $nuevo_estado = $_POST['nuevo_estado'] ?? null;

    if ($pedido_id && in_array($nuevo_estado, ['pendiente', 'preparado', 'listo', 'entregado'])) {
        require_once __DIR__ . '/../../controladores/PedidoController.php';
        $controller = new PedidoController();
        $controller->cambiarEstado((int)$pedido_id, $nuevo_estado);
    }
}

header('Location: empleado.php');
exit;
