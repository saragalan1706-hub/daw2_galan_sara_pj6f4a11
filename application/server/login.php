<?php
function readUsers() {
    $path = __DIR__ . "/../../users/users.json";
    $json = file_get_contents($path);
    return json_decode($json, true);
}

function checkUser($users, $user, $password) {
    foreach ($users as $u) {
        if ($u["user"] === $user && $u["password"] === $password) {
            return true;
        }
    }
    return false;
}

// TEMPORAL: $_REQUEST para probar desde la URL
$user = $_REQUEST["nom"] ?? "";
$password = $_REQUEST["contra"] ?? "";

$users = readUsers();
if (checkUser($users, $user, $password)) {
    header("Location: ../client/menu-operaciones.html");
    exit;
} else {
    echo "ERROR";
}