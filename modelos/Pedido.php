<?php
require_once __DIR__ . '/../config/Conexion.php';

class Pedido {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->iniciar();
    }

    public function crear($usuario_id, $total) {
        $usuario_id = (int)$usuario_id;
        $total = (float)$total;
        $sql = "INSERT INTO pedidos (usuario_id, total, fecha_pedido) 
                VALUES ($usuario_id, $total, NOW())";
        return $this->conn->exec($sql);
    }

    public function actualizarEstado($pedido_id, $nuevo_estado) {
        $pedido_id = (int)$pedido_id;
        $nuevo_estado = addslashes($nuevo_estado);
        $sql = "UPDATE pedidos SET estado = '$nuevo_estado' WHERE id = $pedido_id";
        return $this->conn->exec($sql);
    }

    public function eliminar($pedido_id) {
        $pedido_id = (int)$pedido_id;
        $sql = "DELETE FROM pedidos WHERE id = $pedido_id";
        return $this->conn->exec($sql);
    }

    public function listar() {
        $sql = "SELECT p.*, u.nombre AS cliente 
                FROM pedidos p 
                JOIN usuarios u ON u.id = p.usuario_id 
                ORDER BY p.fecha_pedido DESC";
        $resultado = $this->conn->query($sql);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM pedidos WHERE id = $id";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidoPendiente($usuario_id) {
        $usuario_id = (int)$usuario_id;
        $sql = "SELECT * FROM pedidos WHERE usuario_id = $usuario_id AND estado = 'pendiente'";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDetallePedido($pedido_id) {
        $pedido_id = (int)$pedido_id;
        $sql = "SELECT * FROM pedido_detalle WHERE pedido_id = $pedido_id";
        $resultado = $this->conn->query($sql);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosPorUsuario($usuario_id) {
        $usuario_id = (int)$usuario_id;
        $sql = "SELECT * FROM pedidos WHERE usuario_id = $usuario_id ORDER BY fecha_pedido DESC";
        $resultado = $this->conn->query($sql);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarLibroAlPedido($usuario_id, $titulo, $precio) {
        $usuario_id = (int)$usuario_id;
        $titulo = addslashes($titulo);
        $precio = (float)$precio;

        // Buscar pedido pendiente
        $sql = "SELECT id FROM pedidos WHERE usuario_id = $usuario_id AND estado = 'pendiente'";
        $resultado = $this->conn->query($sql);
        $pedido = $resultado->fetch(PDO::FETCH_ASSOC);

        if ($pedido) {
            $pedido_id = $pedido['id'];
        } else {
            $sql = "INSERT INTO pedidos (usuario_id, estado, total, fecha_pedido)
                    VALUES ($usuario_id, 'pendiente', 0, NOW())";
            $this->conn->exec($sql);
            $pedido_id = $this->conn->lastInsertId();
        }

        // Insertar detalle
        $sql = "INSERT INTO pedido_detalle (pedido_id, titulo, precio)
                VALUES ($pedido_id, '$titulo', $precio)";
        $this->conn->exec($sql);

        // Actualizar total
        $sql = "UPDATE pedidos SET total = (
                    SELECT SUM(precio) FROM pedido_detalle WHERE pedido_id = $pedido_id
                ) WHERE id = $pedido_id";
        $this->conn->exec($sql);

        return true;
    }

    public function listarConDetalles() {
        $sql = "SELECT p.*, u.nombre AS cliente, 
                       GROUP_CONCAT(d.titulo SEPARATOR ', ') AS libros 
                FROM pedidos p 
                JOIN usuarios u ON u.id = p.usuario_id 
                LEFT JOIN pedido_detalle d ON d.pedido_id = p.id 
                GROUP BY p.id 
                ORDER BY p.fecha_pedido ASC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarSiEsDelUsuario($pedido_id, $usuario_id) {
        $pedido_id = (int)$pedido_id;
        $usuario_id = (int)$usuario_id;

        $sql = "SELECT COUNT(*) AS total FROM pedidos WHERE id = $pedido_id AND usuario_id = $usuario_id";
        $verificacion = $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($verificacion['total'] == 0) {
            return false;
        }

        $this->conn->exec("DELETE FROM pedido_detalle WHERE pedido_id = $pedido_id");
        $this->conn->exec("DELETE FROM pedidos WHERE id = $pedido_id");

        return true;
    }

    public function obtenerPorId($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM pedidos WHERE id = $id";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminarPedidoConDetalles($id) {
        $id = (int)$id;
        $this->conn->exec("DELETE FROM pedido_detalle WHERE pedido_id = $id");
        return $this->conn->exec("DELETE FROM pedidos WHERE id = $id");
    }

    public function eliminarPorUsuario($pedido_id, $usuario_id) {
        $pedido_id = (int)$pedido_id;
        $usuario_id = (int)$usuario_id;

        $sqlVerificar = "SELECT COUNT(*) AS total FROM pedidos WHERE id = $pedido_id AND usuario_id = $usuario_id";
        $verificacion = $this->conn->query($sqlVerificar)->fetch(PDO::FETCH_ASSOC);

        if ($verificacion['total'] == 0) {
            return false;
        }

        $this->conn->exec("DELETE FROM pedido_detalle WHERE pedido_id = $pedido_id");
        $this->conn->exec("DELETE FROM pedidos WHERE id = $pedido_id");

        return true;
    }
}
