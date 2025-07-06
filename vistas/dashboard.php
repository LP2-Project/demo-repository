<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo'])) {
    header('Location: login.php'); // como está en /vistas/
    exit;
}

// redirige al panel correspondiente según el tipo de usuario
switch ($_SESSION['tipo']) {
    case 'cliente':
        header('Location: cliente/cliente.php'); // está en /vistas/cliente/
        break;
    case 'empleado':
        header('Location: ../empleado.php'); // está en raíz del proyecto
        break;
    case 'administrador':
        header('Location: ../admin.php'); // también está en raíz
        break;
    default:
        // Si algo falla, destruir la sesión y redirigir al login
        session_destroy();
        header('Location: login.php');
        break;
}
exit;