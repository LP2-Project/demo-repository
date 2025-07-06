<?php
require_once __DIR__ . '/../modelos/Producto.php';


class ProductoController {
    public function guardar(array $datos) {
        $producto = new Producto();
        return $producto->guardar(
            $datos['nombre'],
            $datos['descripcion'],
            $datos['autor'],
            $datos['precio'],
            $datos['stock'],
            $datos['imagen']
        );
    }

    public function listar() {
        $producto = new Producto();
        return $producto->listar();
    }

    public function buscar(int $id) {
        $producto = new Producto();
        return $producto->buscar($id);
    }

    public function actualizar(array $datos) {
        $producto = new Producto();
        return $producto->actualizar(
            $datos['id'],
            $datos['nombre'],
            $datos['descripcion'],
            $datos['autor'],
            $datos['precio'],
            $datos['stock'],
            $datos['imagen']
        );
    }

    public function eliminar(int $id) {
        $producto = new Producto();
        return $producto->eliminar($id);
    }
    public function obtenerStockBajo($minimo = 3) {
    $producto = new Producto();
    return $producto->getStockBajo($minimo);
}

}
