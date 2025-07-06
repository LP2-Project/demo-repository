<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit;
}

// Verifica si se recibió el ID del pedido por POST
$pedido_id = $_POST['pedido_id'] ?? null;
if (!$pedido_id) {
    header('Location: carrito.php');
    exit;
}

// Incluir el controlador correcto
require_once __DIR__ . '/../../controladores/PedidoController.php';

$pedidoController = new PedidoController();

// Cambiar estado del pedido a "preparado"
$pedidoController->cambiarEstado($pedido_id, 'preparado');

// Redirigir a la página de pedidos
header('Location: orders.php');
exit;
