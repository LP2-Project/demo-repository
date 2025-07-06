<?php
require_once __DIR__ . '/../config/Conexion.php';

class Usuario {
    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->iniciar();
    }

    public function guardar($nombre, $correo, $contrasena, $tipo) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, tipo, fecha_registro)
                VALUES ('$nombre', '$correo', '$hash', '$tipo', NOW())";
        return $this->conn->exec($sql);
    }

    public function mostrar() {
        $sql = "SELECT id, nombre, correo, tipo, fecha_registro FROM usuarios ORDER BY fecha_registro DESC";
        return $this->conn->query($sql);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = $id";
        return $this->conn->exec($sql);
    }

    public function buscar($id) {
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($nombre, $correo, $tipo, $id) {
        $sql = "UPDATE usuarios SET nombre = '$nombre', correo = '$correo', tipo = '$tipo' WHERE id = $id";
        return $this->conn->exec($sql);
    }

    public function login($correo, $contrasena) {
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $resultado = $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);
        if ($resultado && password_verify($contrasena, $resultado['contrasena'])) {
            return $resultado;
        } else {
            return false;
        }
    }

    public function getTipo($correo) {
        $sql = "SELECT tipo FROM usuarios WHERE correo = ?";
        $query = $this->conn->prepare($sql);
        $query->execute([$correo]);
        return $query->fetchColumn();
    }

    public function getIdPorCorreo($correo) {
        $sql = "SELECT id FROM usuarios WHERE correo = ?";
        $query = $this->conn->prepare($sql);
        $query->execute([$correo]);
        return $query->fetchColumn();
    }

    public function buscarPorCorreo($correo) {
        $correo = addslashes($correo);
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }
    public function verificarContrasena($id, $contrasena) {
    $db = new Conexion();
    $conn = $db->iniciar();

    $usuarios = $conn->query("SELECT contrasena FROM usuarios WHERE id = $id")->fetchAll(PDO::FETCH_ASSOC);

    if (count($usuarios) === 0) {
        return false;
    }

    $hash = $usuarios[0]['contrasena'];

    return password_verify($contrasena, $hash);
}
public function cambiarContrasena($id, $nuevaContrasena) {
    $db = new Conexion();
    $conn = $db->iniciar();

    $hash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET contrasena = '$hash' WHERE id = $id";

    return $conn->exec($sql) > 0;
}
public function actualizarUsuario($id, $nombre, $correo) {
    $db = new Conexion();
    $conn = $db->iniciar();

    $sql = "UPDATE usuarios SET nombre = '$nombre', correo = '$correo' WHERE id = $id";
    return $conn->exec($sql) > 0;
}

}
