<?php
require_once __DIR__ . '/../../config/Conexion.php';

$db = new Conexion();
$conn = $db->iniciar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $nombre = addslashes(trim($_POST['nombre']));
    $correo = addslashes(trim($_POST['correo']));
    $tipo = addslashes($_POST['tipo']);

    $sql = "UPDATE usuarios SET nombre = '$nombre', correo = '$correo', tipo = '$tipo' WHERE id = $id";
    $conn->exec($sql);

    header('Location: admin.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM usuarios WHERE id = $id";
$resultado = $conn->query($sql);
$usuario = $resultado->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 20px; }
        form { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 4px; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Editar Usuario</h2>
    <form method="post" action="editar_usuario.php">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

        <label>Correo</label>
        <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

        <label>Tipo</label>
        <select name="tipo">
            <option value="cliente" <?= $usuario['tipo'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
            <option value="empleado" <?= $usuario['tipo'] === 'empleado' ? 'selected' : '' ?>>Empleado</option>
            <option value="administrador" <?= $usuario['tipo'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
        </select>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
