<?php
// Paso 1: función que lee la lista de usuarios (users.json)
function readUsers() {
    $path = __DIR__ . "/../../users/users.json";
    $json = file_get_contents($path);       // lee el texto del fichero
    return json_decode($json, true);        // lo convierte en array asociativo
}

// Paso 2: función que comprueba si el usuario y la contraseña están en la lista
function checkUser($users, $user, $password) {
    foreach ($users as $u) {
        if ($u["user"] === $user && $u["password"] === $password) {
            return true;
        }
    }
    return false;
}

// Paso 3: recoger lo que envía el cliente (POST)
$user = $_POST["user"] ?? "";
$password = $_POST["password"] ?? "";

// Paso 4: responder solo con el resultado
$users = readUsers();
if (checkUser($users, $user, $password)) {
    echo "OK";
} else {
    echo "ERROR";
}