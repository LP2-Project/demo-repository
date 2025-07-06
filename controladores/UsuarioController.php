<?php
require_once __DIR__ . '/../modelos/Usuario.php';


class UsuarioController {
    public function guardar(array $datos) {
        $usuario = new Usuario();
        $resultado = $usuario->guardar(
            $datos['nombre'],
            $datos['correo'],
            $datos['contrasena'],
            $datos['tipo']
        );
        return $resultado ? "Usuario registrado correctamente" : "Error al registrar";
    }

    public function mostrar() {
        $usuario = new Usuario();
        return $usuario->mostrar();
    }

    public function eliminar(int $id) {
        $usuario = new Usuario();
        $resultado = $usuario->eliminar($id);
        return $resultado ? "Usuario eliminado" : "Error al eliminar";
    }

    public function buscar(int $id) {
        $usuario = new Usuario();
        return $usuario->buscar($id);
    }

    public function actualizar(array $datos) {
        $usuario = new Usuario();
        $resultado = $usuario->actualizar(
            $datos['nombre'],
            $datos['correo'],
            $datos['tipo'],
            $datos['id']
        );
        return $resultado ? "Usuario actualizado" : "Error al actualizar";
    }

    public function login($correo, $contrasena) {
        $usuario = new Usuario();
        return $usuario->login($correo, $contrasena);
    }
    public function getTipo($correo) {
    $usuario = new Usuario();
    return $usuario->getTipo($correo);
    }
    public function getIdPorCorreo($correo) {
        $usuario = new Usuario();
        return $usuario->getIdPorCorreo($correo);
    }
    public function obtenerUsuarioPorCorreo($correo) {
    $usuario = new Usuario();
    return $usuario->buscarPorCorreo($correo);
}
public function listar() {
    $usuario = new Usuario();
    return $usuario->mostrar();  // este método está en el modelo
}
public function verificarContrasena($id, $contrasena) {
    $usuario = new Usuario();
    return $usuario->verificarContrasena($id, $contrasena);
}
public function cambiarContrasena($id, $nuevaContrasena) {
    $usuario = new Usuario();
    return $usuario->cambiarContrasena($id, $nuevaContrasena);
}
public function actualizarUsuario($id, $nombre, $correo) {
    $usuario = new Usuario();
    return $usuario->actualizarUsuario($id, $nombre, $correo);
}


}
