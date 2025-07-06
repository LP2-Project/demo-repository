<?php
session_start();
require_once __DIR__ . '/../../controladores/UsuarioController.php';

if (!isset($_SESSION['correo'])) {
    header('Location: login.php');
    exit;
}

$usuarioController = new UsuarioController();
$correoSesion = $_SESSION['correo'];
$usuario = $usuarioController->obtenerUsuarioPorCorreo($correoSesion);

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['actualizar_datos'])) {
        $nombre = trim($_POST['nombre']);
        $correo = trim($_POST['correo']);

        if ($usuarioController->actualizarUsuario($usuario['id'], $nombre, $correo)) {
            $_SESSION['correo'] = $correo;
            $mensaje = 'Datos actualizados correctamente.';
            $usuario = $usuarioController->obtenerUsuarioPorCorreo($correo);
        } else {
            $error = 'Error al actualizar los datos.';
        }
    }

    if (isset($_POST['cambiar_contrasena'])) {
        $actual = $_POST['contrasena_actual'];
        $nueva = $_POST['nueva_contrasena'];

        if ($usuarioController->verificarContrasena($usuario['id'], $actual)) {
            if ($usuarioController->cambiarContrasena($usuario['id'], $nueva)) {
                $mensaje = 'Contraseña actualizada correctamente.';
            } else {
                $error = 'No se pudo cambiar la contraseña.';
            }
        } else {
            $error = 'La contraseña actual es incorrecta.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
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
            max-width: 800px;
            margin: 30px auto;
            background-color: white;
            border-radius: 12px;
            padding: 25px 35px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: 500;
            color: #34495e;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .mensaje {
            color: green;
            text-align: center;
            font-weight: bold;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        .bloque {
            margin-bottom: 40px;
        }

        h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="../cliente/cliente.php" class="inicio-link">← Volver al inicio</a>
    Panel del Usuario - Perfil
</div>

<?php if ($mensaje): ?>
    <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="panel">
    <div class="bloque">
        <h3>✏️ Editar Datos Personales</h3>
        <form method="post">
            <input type="hidden" name="actualizar_datos" value="1">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

            <button type="submit">Actualizar Datos</button>
        </form>
    </div>

    <div class="bloque">
        <h3>🔒 Cambiar Contraseña</h3>
        <form method="post">
            <input type="hidden" name="cambiar_contrasena" value="1">
            <label for="contrasena_actual">Contraseña actual</label>
            <input type="password" id="contrasena_actual" name="contrasena_actual" required>

            <label for="nueva_contrasena">Nueva contraseña</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>

            <button type="submit">Cambiar Contraseña</button>
        </form>
    </div>
</div>

</body>
</html>
