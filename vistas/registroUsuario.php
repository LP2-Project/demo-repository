<?php
session_start();
require_once __DIR__ . '/../controladores/UsuarioController.php';

$usuarioController = new UsuarioController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = trim($_POST['nombre']);
    $correo     = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $tipo       = 'cliente';

    try {
        $usuarioController->registrar($nombre, $correo, $contrasena, $tipo);
        header('Location: login.php');
        exit;
    } catch (Exception $e) {
        $error = 'Hubo un problema al registrar: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Librería</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #dbeeff, #f4f9fd);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .registro-container {
            width: 100%;
            max-width: 400px;
            margin: 80px auto;
            background: #ffffff;
            padding: 35px 40px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            border-radius: 14px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 28px;
            font-size: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd6e2;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            background-color: #f9fbfd;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #2980b9;
            background-color: #ffffff;
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #2980b9;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        button[type="submit"]:hover {
            background-color: #2471a3;
            transform: scale(1.01);
        }

        .error-message {
            color: #e74c3c;
            margin-bottom: 18px;
            text-align: center;
            font-weight: 500;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #555;
        }

        .login-link a {
            color: #2980b9;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <h1>Regístrate como Cliente</h1>

        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif ?>

        <form method="post" action="registroUsuario.php">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Registrarme</button>
        </form>

        <div class="login-link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>
