<?php
// Lee users.json y devuelve el array de usuarios (vacío si no se puede leer)
function readUsers(): array {
    $path = __DIR__ . "/../../users/users.json";   // Ruta al archivo de usuarios
    if (!file_exists($path)) {
        return [];                                 // Sin archivo = ningún usuario válido
    }
    $json  = file_get_contents($path);             // Lee el texto del archivo
    $users = json_decode($json, true);             // Texto JSON -> array asociativo
    return is_array($users) ? $users : [];
}

// Comprueba si usuario y contraseña coinciden con alguno de la lista.
function checkUser(array $users, string $username, string $password): bool {
    foreach ($users as $u) {
        if ($u["user"] === $username && $u["password"] === $password) {
            return true;
        }
    }
    return false;
}

// Las credenciales llegan por POST; cualquier otro método se rechaza
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit;
}

$username = trim($_POST["username"] ?? "");   // trim quita espacios sobrantes
$password = $_POST["password"] ?? "";

$users = readUsers();

header("Content-Type: text/plain; charset=UTF-8");   // respuesta solo texto, sin HTML

if (checkUser($users, $username, $password)) {
    http_response_code(200);                          // 200 OK: usuario válido
    echo "OK";
} else {
    http_response_code(401);                          // 401: no se ha podido validar
    echo "Authentication failed";
}