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
            background: linear-gradient(to right, #dbeeff, #f4f9fd);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding-top: 10px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 12px;
            margin-top: -30px;
            color: #2c3e50;
        }

        .welcome-text h2 {
            font-size: 26px;
            margin-bottom: 6px;
        }

        .welcome-text p {
            font-size: 15px;
            color: #555;
            margin-top: 0;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
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

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #2980b9;
            outline: none;
            background-color: #ffffff;
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

        .register-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #555;
        }

        .register-link a {
            color: #2980b9;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="welcome-text">
        <h2>Bienvenido a la Librería Digital</h2>
        <p>Accede para explorar nuestro catálogo y realizar tus pedidos.</p>
    </div>

    <div class="login-container">
        <div class="icon-user"><i class="fas fa-user-circle"></i></div>
        <h1>Iniciar Sesión</h1>

        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif ?>

        <form method="post" action="login.php">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>

            <button type="submit">Iniciar sesión</button>
        </form>

        <div class="register-link">
            ¿No tienes cuenta? <a href="registroUsuario.php">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>
