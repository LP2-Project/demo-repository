<?php
require_once __DIR__ . '/../config/Conexion.php';

class Producto {
    public function guardar($nombre, $descripcion, $autor, $precio, $stock, $imagen) {
        $conn = new Conexion();
        $conexion = $conn->iniciar();
        $sql = "INSERT INTO productos (nombre, descripcion, autor, precio, stock, imagen_url)
                VALUES ('$nombre', '$descripcion', '$autor', $precio, $stock, '$imagen')";
        $resultado = $conexion->exec($sql);
        $conn->terminar();
        return $resultado;
    }

    public function listar() {
        $conn = new Conexion();
        $conexion = $conn->iniciar();
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        $resultado = $conexion->query($sql);
        $conn->terminar();
        return $resultado;
    }

    public function buscar($id) {
        $conn = new Conexion();
        $conexion = $conn->iniciar();
        $sql = "SELECT * FROM productos WHERE id = $id";
        $resultado = $conexion->query($sql);
        $conn->terminar();
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($id) {
        $conn = new Conexion();
        $conexion = $conn->iniciar();
        $sql = "DELETE FROM productos WHERE id = $id";
        $resultado = $conexion->exec($sql);
        $conn->terminar();
        return $resultado;
    }

    public function actualizar($id, $nombre, $descripcion, $autor, $precio, $stock, $imagen) {
        $conn = new Conexion();
        $conexion = $conn->iniciar();
        $sql = "UPDATE productos SET 
                    nombre = '$nombre',
                    descripcion = '$descripcion',
                    autor = '$autor',
                    precio = $precio,
                    stock = $stock,
                    imagen_url = '$imagen'
                WHERE id = $id";
        $resultado = $conexion->exec($sql);
        $conn->terminar();
        return $resultado;
    }
    public function guardarDesdeApi($nombre, $descripcion, $autor, $precio, $stock, $imagen_url) {
        $db = new Conexion();
        $conn = $db->iniciar();

        $sql = "INSERT INTO productos (nombre, descripcion, autor, precio, stock, imagen_url)
                VALUES (?, ?, ?, ?, ?, ?)";
        $conn->prepare($sql)->execute([$nombre, $descripcion, $autor, $precio, $stock, $imagen_url]);

        return true;
    }
    public function getStockBajo($minimo) {
    $conexion = (new Conexion())->iniciar();
    $sql = "SELECT * FROM productos WHERE stock < $minimo";
    $resultado = $conexion->query($sql);
    return $resultado;
}

}
