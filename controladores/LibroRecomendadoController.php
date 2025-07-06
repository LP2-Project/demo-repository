<?php
require_once __DIR__ . '/../modelos/ProductoModel.php';

class LibroRecomendadoController {
    private $model;

    public function __construct() {
        $this->model = new ProductoModel();
    }

    public function obtenerLibrosRecomendados() {
        return $this->model->obtenerTodos(); // o ->obtenerRecomendados(20) si quieres solo algunos
    }
}
