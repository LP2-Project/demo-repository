<?php
require_once __DIR__ . '/../../config/Conexion.php';

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$pedido_id = (int) $_GET['id'];
$db = new Conexion();
$conn = $db->iniciar();

// Verificar si el pedido existe
$verificar = $conn->query("SELECT * FROM pedidos WHERE id = $pedido_id");
if ($verificar->rowCount() === 0) {
    header('Location: admin.php');
    exit;
}

// Eliminar primero los detalles
$conn->exec("DELETE FROM pedido_detalle WHERE pedido_id = $pedido_id");

// Luego eliminar el pedido
$conn->exec("DELETE FROM pedidos WHERE id = $pedido_id");

header('Location: admin.php');
exit;
?>
