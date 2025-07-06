<?php
session_start();
require_once __DIR__ . '/../../controladores/ProductoController.php';

$controller = new ProductoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear'])) {
        $mensaje = $controller->guardar($_POST);
        header("Location: agregar_libros.php?mensaje=" . urlencode($mensaje));
        exit();
    }

    if (isset($_POST['actualizar'])) {
        $mensaje = $controller->actualizar($_POST);
        header("Location: agregar_libros.php?mensaje=" . urlencode($mensaje));
        exit();
    }

    if (isset($_POST['eliminar'])) {
        $mensaje = $controller->eliminar((int)$_POST['id']);
        header("Location: agregar_libros.php?mensaje=" . urlencode($mensaje));
        exit();
    }
}

$libros = $controller->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Libros</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            padding: 16px;
            color: white;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .navbar {
            background-color: #ecf0f1;
            display: flex;
            justify-content: flex-end;
            padding: 10px 30px;
            gap: 10px;
            align-items: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        .navbar a {
            text-decoration: none;
            background-color: #2980b9;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #1f5980;
        }

        .navbar .activo {
            background-color: #1a5276;
        }

        .panel {
            max-width: 1000px;
            margin: 30px auto;
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .titulo-seccion {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 16px;
        }

        .formulario {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .formulario input {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            flex: 1 1 30%;
        }

        .formulario button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .formulario button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 15px;
        }

        th {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table input {
            width: 100%;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .acciones {
            display: flex;
            gap: 6px;
        }

        .azul {
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
        }

        .rojo {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
        }

        .azul:hover { background-color: #1f618d; }
        .rojo:hover { background-color: #c0392b; }
    </style>
</head>
<body>

<header>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Panel del Empleado - Gestión de Libros</h1>
        <form method="post" action="../logout.php">
            <button type="submit" style="background-color: #e74c3c; color: white; padding: 8px 16px; font-size: 14px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; display: flex; align-items: center;">
                <img src="https://cdn-icons-png.freepik.com/512/16587/16587480.png" alt="Salir" style="width: 20px; height: 20px; margin-right: 6px;">
                Cerrar sesión
            </button>
        </form>
    </div>
</header>

<div class="navbar">
    <a href="stock_bajo.php">Información de stock</a>
    <a href="agregar_libros.php" class="activo">Gestionar Libros</a>
    <a href="empleado.php">Lista de Pedidos</a>
    <a href="ventas.php">Ventas</a>
</div>

<div class="panel">
    <div class="titulo-seccion">Registrar nuevo libro</div>
    <form class="formulario" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="descripcion" placeholder="Descripción">
        <input type="text" name="autor" placeholder="Autor" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="text" name="imagen_url" placeholder="Imagen URL">
        <button type="submit" name="crear">Crear</button>
    </form>
</div>

<div class="panel">
    <div class="titulo-seccion">Listado de libros registrados</div>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Autor</th><th>Precio</th><th>Stock</th><th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($libros as $libro): ?>
            <tr>
                <form method="POST">
                    <td><?= $libro['id'] ?><input type="hidden" name="id" value="<?= $libro['id'] ?>"></td>
                    <td><input type="text" name="nombre" value="<?= htmlspecialchars($libro['nombre']) ?>"></td>
                    <td><input type="text" name="autor" value="<?= htmlspecialchars($libro['autor']) ?>"></td>
                    <td><input type="number" step="0.01" name="precio" value="<?= $libro['precio'] ?>"></td>
                    <td><input type="number" name="stock" value="<?= $libro['stock'] ?>"></td>
                    <td class="acciones">
                        <input type="hidden" name="descripcion" value="<?= htmlspecialchars($libro['descripcion']) ?>">
                        <input type="hidden" name="imagen_url" value="<?= htmlspecialchars($libro['imagen_url']) ?>">
                        <button name="actualizar" class="azul">Actualizar</button>
                        <button name="eliminar" class="rojo" onclick="return confirm('¿Estás seguro de eliminar este libro?')">Eliminar</button>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
