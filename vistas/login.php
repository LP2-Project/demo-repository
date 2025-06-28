<?php
session_start();
require_once __DIR__ . '/../controladores/UsuarioController.php';

$usuarioController = new UsuarioController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo     = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    if ($usuarioController->login($correo, $contrasena)) {
        $_SESSION['correo'] = $correo;
        $_SESSION['tipo']   = $usuarioController->getTipo($correo);

        if ($_SESSION['tipo'] === 'cliente') {
            header('Location: ../cliente.php');
        } else {
            header('Location: ../dashboard.php');
        }
        exit;
    } else {
        $error = 'Correo o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Librería</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 30px 40px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #34495e;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #1c6691;
        }

        .error-message {
            color: #e74c3c;
            margin-bottom: 20px;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .register-link a {
            color: #2980b9;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>

        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif ?>

        <form method="post" action="login.php">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Iniciar sesión</button>
        </form>

        <div class="register-link">
            ¿No tienes cuenta? <a href="registroUsuario.php">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>
