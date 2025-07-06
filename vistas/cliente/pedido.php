<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: vistas/login.php');
    exit;
}

require_once __DIR__ . '/../../controladores/PedidoController.php';

$title = $_POST['title'] ?? '';
$price = $_POST['price'] ?? 0;
$usuario_id = $_SESSION['id_usuario'];

if (empty($title) || empty($price)) {
    header('Location: cliente.php');
    exit;
}

// Lógica de negocio usando el controlador
$controller = new PedidoController();
$controller->agregarLibro($usuario_id, $title, $price);

header('Location: carrito.php');
exit;
