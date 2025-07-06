<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Librería Online</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f2f6fc;
            color: #2c3e50;
        }
        header {
            background-color: #2980b9;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        nav {
            background-color: #3498db;
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 14px 0;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .main-content {
            padding: 40px;
            text-align: center;
        }
        footer {
            background-color: #ecf0f1;
            text-align: center;
            padding: 20px;
            margin-top: 60px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <h1>📚 Librería Online</h1>
    </header>

    <nav>
        <a href="vistas/registroUsuario.php">📝 Registro</a>
        <a href="vistas/login.php">🔑 Login</a>
        <a href="vistas/dashboard.php">📊 Dashboard</a>
    </nav>

    <div class="main-content">
        <h2>Bienvenido a la Librería</h2>
        <p>Descubre una gran variedad de libros y realiza tus pedidos en línea.</p>
    </div>

    <footer>
        © 2025 Librería | Todos los derechos reservados
    </footer>
</body>
</html>
