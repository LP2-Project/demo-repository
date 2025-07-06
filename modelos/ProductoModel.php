<?php
require_once __DIR__ . '/../config/Conexion.php';

class ProductoModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Conexion())->iniciar();
    }

    public function obtenerTodos() {
        return $this->conn->query("SELECT * FROM productos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerRecomendados($limite = 20) {
        return $this->conn->query("SELECT * FROM productos ORDER BY RAND() LIMIT $limite")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerPorNombre($nombre) {
    $nombre = $this->conn->quote($nombre); // Escapamos correctamente
    $sql = "SELECT * FROM productos WHERE nombre = $nombre LIMIT 1";
    return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);
}

public function restarStock($producto_id) {
    $sql = "UPDATE productos SET stock = stock - 1 WHERE id = $producto_id AND stock > 0";
    return $this->conn->exec($sql);
}

}
