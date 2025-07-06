<?php
session_start();
require_once __DIR__ . '/../../controladores/UsuarioController.php';

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = intval($_GET['id']);

$usuarioController = new UsuarioController();
$usuarioController->eliminar($id);

header('Location: admin.php');
exit;
