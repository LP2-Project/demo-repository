<?php
require_once __DIR__ . '/../modelos/Pedido.php';

class PedidoController {
    public function crear(array $datos) {
        $pedido = new Pedido();
        return $pedido->crear($datos['usuario_id'], $datos['total']);
    }

    public function cambiarEstado(int $id, string $nuevoEstado) {
        $pedido = new Pedido();
        return $pedido->actualizarEstado($id, $nuevoEstado);
    }

    public function eliminar(int $id) {
        $pedido = new Pedido();
        return $pedido->eliminar($id);
    }

    public function listar() {
        $pedido = new Pedido();
        return $pedido->listar();
    }

    public function buscar(int $id) {
        $pedido = new Pedido();
        return $pedido->buscar($id);
    }

    public function obtenerPedidoPendiente($usuario_id) {
        $pedido = new Pedido();
        return $pedido->obtenerPedidoPendiente($usuario_id);
    }

    public function obtenerDetallePedido($pedido_id) {
        $pedido = new Pedido();
        return $pedido->obtenerDetallePedido($pedido_id);
    }

    public function obtenerPedidosPorUsuario($usuario_id) {
        $pedido = new Pedido();
        return $pedido->obtenerPedidosPorUsuario($usuario_id);
    }
    public function agregarLibro($usuario_id, $titulo, $precio) {
    $pedido = new Pedido();
    return $pedido->agregarLibroAlPedido($usuario_id, $titulo, $precio);
}
public function listarConDetalles() {
    $pedido = new Pedido();
    return $pedido->listarConDetalles();
}
public function eliminarPedidoConValidacion($pedido_id, $usuario_id) {
    $pedido = new Pedido();
    return $pedido->eliminarSiEsDelUsuario($pedido_id, $usuario_id);
}
public function eliminarPedidoPorUsuario($pedido_id, $usuario_id) {
    $pedido = new Pedido();
    return $pedido->eliminarSiEsDelUsuario($pedido_id, $usuario_id);
}
public function eliminarPedidoConDetalles($id) {
    $pedido = new Pedido();
    return $pedido->eliminarPedidoConDetalles($id);
}
public function eliminarPedidoUsuario($pedido_id, $usuario_id) {
    $pedido = new Pedido();
    return $pedido->eliminarPorUsuario($pedido_id, $usuario_id);
}
public function eliminarPorUsuario($pedido_id, $usuario_id) {
    $pedido = new Pedido();
    return $pedido->eliminarPorUsuario($pedido_id, $usuario_id);
}
public function realizarPedido($usuario_id, $nombreProducto, $precio) {
        $producto = $this->productoModel->obtenerPorNombre($nombreProducto);

        if ($producto && $producto['stock'] > 0) {
            $sql = "INSERT INTO pedidos (usuario_id, producto_id, total, fecha_pedido)
                    VALUES ($usuario_id, {$producto['id']}, $precio, NOW())";
            $this->conn->exec($sql);

            // Restar stock
            $this->productoModel->restarStock($producto['id']);

            return "ok";
        } elseif ($producto && $producto['stock'] <= 0) {
            return "agotado";
        } else {
            return "no_encontrado";
        }
    }


}
