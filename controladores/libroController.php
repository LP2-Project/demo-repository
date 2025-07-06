<?php
require_once __DIR__ . '/../config/Conexion.php';

class LibroController {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->iniciar();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        $resultado = $this->conn->query($sql);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $descripcion, $autor, $precio, $stock, $imagen_url) {
        $nombre = addslashes($nombre);
        $descripcion = addslashes($descripcion);
        $autor = addslashes($autor);
        $precio = (float)$precio;
        $stock = (int)$stock;
        $imagen_url = addslashes($imagen_url);

        $sql = "INSERT INTO productos (nombre, descripcion, autor, precio, stock, imagen_url)
                VALUES ('$nombre', '$descripcion', '$autor', $precio, $stock, '$imagen_url')";
        return $this->conn->exec($sql);
    }

    public function actualizar($id, $nombre, $descripcion, $autor, $precio, $stock, $imagen_url) {
        $id = (int)$id;
        $nombre = addslashes($nombre);
        $descripcion = addslashes($descripcion);
        $autor = addslashes($autor);
        $precio = (float)$precio;
        $stock = (int)$stock;
        $imagen_url = addslashes($imagen_url);

        $sql = "UPDATE productos SET 
                    nombre = '$nombre',
                    descripcion = '$descripcion',
                    autor = '$autor',
                    precio = $precio,
                    stock = $stock,
                    imagen_url = '$imagen_url'
                WHERE id = $id";
        return $this->conn->exec($sql);
    }

    public function eliminar($id) {
        $id = (int)$id;
        $sql = "DELETE FROM productos WHERE id = $id";
        return $this->conn->exec($sql);
    }
}
