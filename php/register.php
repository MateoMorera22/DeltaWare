<?php
include 'conexion.php'; // Conexión a la base de datos

// Activar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Establece el encabezado para respuesta JSON

$response = ["success" => false, "message" => ""];

try {
    // Verificar conexión a la base de datos
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión a la base de datos: " . $conexion->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capturar y validar todos los campos
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? '';
        $birthdate = trim($_POST['birthdate'] ?? '');
        $identificacion = trim($_POST['cedula'] ?? ''); // Cambiamos el nombre de la variable a $identificacion

        // Verificar que todos los campos estén completos
        if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($rol) || empty($birthdate) || empty($identificacion)) {
            throw new Exception("Todos los campos son obligatorios.");
        }

        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Verificar si el usuario ya existe en la tabla `padres`
        $queryPadre = "SELECT * FROM padres WHERE email = ? OR identificacion = ?";
        $stmtPadre = $conexion->prepare($queryPadre);
        if (!$stmtPadre) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }
        $stmtPadre->bind_param("ss", $email, $identificacion);
        $stmtPadre->execute();
        $resultadoPadre = $stmtPadre->get_result();

        // Verificar si el usuario ya existe en la tabla `maestros`
        $queryMaestro = "SELECT * FROM maestros WHERE email = ? OR identificacion = ?";
        $stmtMaestro = $conexion->prepare($queryMaestro);
        if (!$stmtMaestro) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }
        $stmtMaestro->bind_param("ss", $email, $identificacion);
        $stmtMaestro->execute();
        $resultadoMaestro = $stmtMaestro->get_result();

        if ($resultadoPadre->num_rows > 0 || $resultadoMaestro->num_rows > 0) {
            $response["message"] = "El usuario o la identificación ya existe en el sistema. Intenta con otro correo o identificación.";
        } else {
            if ($rol == 1) { // Rol de Profesor
                $queryInsertMaestro = "INSERT INTO maestros (nombre, apellido, email, password, id_rol, fecha_nacimiento, identificacion) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtInsertMaestro = $conexion->prepare($queryInsertMaestro);
                if (!$stmtInsertMaestro) {
                    throw new Exception("Error al preparar la consulta: " . $conexion->error);
                }
                $stmtInsertMaestro->bind_param("ssssiss", $nombre, $apellido, $email, $passwordHash, $rol, $birthdate, $identificacion);

                if ($stmtInsertMaestro->execute()) {
                    $response["success"] = true;
                    $response["message"] = "El usuario profesor ha sido registrado exitosamente.";
                } else {
                    $response["message"] = "No se pudo registrar al profesor. Inténtalo de nuevo.";
                }
            } elseif ($rol == 2) { // Rol de Tutor
                $queryInsertTutor = "INSERT INTO padres (nombre, apellido, email, password, id_rol, fecha_nacimiento, identificacion) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtInsertTutor = $conexion->prepare($queryInsertTutor);
                if (!$stmtInsertTutor) {
                    throw new Exception("Error al preparar la consulta: " . $conexion->error);
                }
                $stmtInsertTutor->bind_param("ssssiss", $nombre, $apellido, $email, $passwordHash, $rol, $birthdate, $identificacion);

                if ($stmtInsertTutor->execute()) {
                    $response["success"] = true;
                    $response["message"] = "El usuario tutor ha sido registrado exitosamente.";
                } else {
                    $response["message"] = "No se pudo registrar al tutor. Inténtalo de nuevo.";
                }
            } elseif ($rol == 3) { // Rol de Admin
                $queryInsertAdmin= "INSERT INTO administradores (nombre, apellido, email, password, id_rol, fecha_nacimiento, identificacion) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtInsertAdmin= $conexion->prepare($queryInsertAdmin);
                if (!$stmtInsertAdmin) {
                    throw new Exception("Error al preparar la consulta: " . $conexion->error);
                }
                $stmtInsertAdmin->bind_param("ssssiss", $nombre, $apellido, $email, $passwordHash, $rol, $birthdate, $identificacion);

                if ($stmtInsertAdmin->execute()) {
                    $response["success"] = true;
                    $response["message"] = "El usuario Admin ha sido registrado exitosamente.";
                } else {
                    $response["message"] = "No se pudo registrar al Administrador. Inténtalo de nuevo.";
                }
            } else {
                $response["message"] = "Rol no válido. Seleccione un rol correcto.";
            }
        }
    }
} catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = "Error: " . $e->getMessage();
}

echo json_encode($response); // Envía la respuesta JSON
exit;
