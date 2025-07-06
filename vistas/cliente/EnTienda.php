<?php
session_start();
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'cliente') {
    header('Location: vistas/login.php');
    exit;
}

require_once __DIR__ . '/../../controladores/LibroRecomendadoController.php';

$controller = new LibroRecomendadoController();
$libros = $controller->obtenerLibrosRecomendados();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>En Tienda</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .book-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 30px 20px;
        }
        .book-card {
            width: 220px;
            background-color: #ffffff;
            border: 1px solid #d1e9f9;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            padding: 16px;
            text-align: center;
            transition: transform 0.2s;
        }
        .book-card:hover {
            transform: translateY(-5px);
        }
        .book-card img {
            height: 150px;
            object-fit: contain;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .book-card h4 {
            font-size: 16px;
            margin: 10px 0 5px;
            color: #0077b6;
        }
        .book-card p {
            margin: 4px 0;
            font-size: 14px;
            color: #333;
        }
        .book-card button {
            background-color: #00b4d8;
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .book-card button:hover {
            background-color: #0096c7;
        }
        .volver {
            text-align: center;
            margin: 20px;
        }
        .volver a {
            color: #2980b9;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
        }
        .volver a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">📚 Libros En Tienda</div>
    <div class="book-grid">
        <?php foreach ($libros as $libro): ?>
            <div class="book-card">
                <img src="<?= htmlspecialchars($libro['imagen_url'] ?: 'https://via.placeholder.com/150x200?text=Sin+imagen') ?>" alt="Portada">
                <h4><?= htmlspecialchars($libro['nombre']) ?></h4>
                <p><i><?= htmlspecialchars($libro['autor']) ?></i></p>
                <p>US$ <?= number_format($libro['precio'], 2) ?></p>
                <form method="post" action="pedido.php">
                    <input type="hidden" name="title" value="<?= htmlspecialchars($libro['nombre']) ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($libro['precio']) ?>">
                    <button type="submit">Pedir</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="volver">
        <a href="cliente.php">← Volver al Catálogo</a>
    </div>
</body>
</html>
