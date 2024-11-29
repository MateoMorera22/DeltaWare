<?php
include 'conexion.php'; // Conexión a la base de datos

header('Content-Type: application/json');

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $response["message"] = "Todos los campos son obligatorios.";
        echo json_encode($response);
        exit;
    }

    // Verificar si el usuario existe en la tabla maestros
    $query = "SELECT password FROM maestros WHERE email = ?"; // Solo seleccionar la contraseña
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar si la contraseña coincide
        if (password_verify($password, $user['password'])) {
            $response["success"] = true;
            $response["message"] = "Inicio de sesión exitoso.";
        } else {
            $response["message"] = "La contraseña es incorrecta.";
        }
    } else {
        $response["message"] = "El usuario no está registrado.";
    }
}

echo json_encode($response);
exit;
?>
