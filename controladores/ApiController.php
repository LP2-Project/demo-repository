<?php
require_once __DIR__ . '/../modelos/Api.php';

class ApiController {
    public function importarLibros() {
        $url = "https://openlibrary.org/subjects/fiction.json?limit=4000";
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        $producto = new Producto();
        foreach ($data['works'] as $libro) {
            $nombre = $libro['title'];
            $descripcion = isset($libro['description']) ? $libro['description'] : 'Sin descripción';
            $autor = implode(", ", $libro['authors']);
            $precio = 20.00;
            $stock = 100;
            $imagen_url = $libro['cover']['large'] ?? '';

            $producto->guardarDesdeApi($nombre, $descripcion, $autor, $precio, $stock, $imagen_url);
        }

        return "Libros importados correctamente.";
    }
}
