<?php

const ORDERS_DIR  = __DIR__ . '/../../orders';          // carpeta "orders"
const ORDERS_FILE = ORDERS_DIR . '/orders.json';         // archivo "orders.json" dentro de esa carpeta

// Convierte el array de productos de UN pedido en un texto legible
// Se usa más abajo, dentro de la tabla, una vez por cada comanda
function formatProducts(array $products): string {
    $items = [];   // Guarda el texto de cada producto

    foreach ($products as $product) {
        // htmlspecialchars evita que el nombre del producto rompa el HTML si contuviera caracteres como < > " '
        $items[] = htmlspecialchars($product['name'])
                 . ' x' . $product['quantity'] // ej: "x2"
                 . ' (' . number_format($product['price'], 2) . ' €)'; // ej: "(24.99 €)"
    }

    // Une todos los productos en un solo texto, cada uno en su línea (<br>)
    // dentro de la misma celda de la tabla
    return implode('<br>', $items);
}

// Comprobación: ¿existe el archivo de comandas?
// Si no existe, no podemos mostrar nada: devolvemos un error 404 y una pequeña página HTML con botones de vuelta, tal como pide el enunciado.
if (!file_exists(ORDERS_FILE)) {

    http_response_code(404);   // Código de estado HTTP: "no encontrado"

    echo '
    <h2>Error: orders file not found</h2>

    <a href="../client/listar.html">
        <button>Back to orders menu</button>
    </a>

    <a href="../client/menu-operaciones.html">
        <button>Back to operations menu</button>
    </a>

    <a href="../client/index.html">
        <button>Back to home</button>
    </a>
    ';

    exit;   // detiene el script aquí: no se ejecuta nada más
}

// El archivo existe: lo leemos
// file_get_contents() lee todo el texto del archivo (el JSON en bruto)
// json_decode(..., true) lo convierte en un array asociativo de PHP
// (el "true" es lo que hace que sea array y no un objeto)
$orders = json_decode(file_get_contents(ORDERS_FILE), true);

http_response_code(200);   // Todo ha ido bien: código de estado "OK"

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../client/style.css">
    <meta charset="UTF-8">
    <title>All orders</title>
</head>
<body>
    <nav>
        <img class="logo" src="../client/img/logo.png">
        <a href="../client/index.html">Start</a>
        <a href="../client/documentacion.html">Documentation</a>
        <a href="../client/login.html">Log in</a>
        <a href="../client/menu-operaciones.html"><img class="flecha" src="../client/img/flecha.png"></a>
    </nav>

    <div class="resultado-container">
        <div class="resultado-card">
            <h1>All orders</h1>

            <table>

            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Address</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Products</th>
                <th>Total (€)</th>
            </tr>

            <?php
            // foreach recorre el array $orders una comanda a la vez.
            // Cada vuelta del bucle, $order contiene los datos de UNA comanda,
            // y se genera una fila <tr> distinta para cada una.
            foreach($orders as $order): ?>

            <tr>
                <td><?= htmlspecialchars($order['orderId']) ?></td>
                <td><?= htmlspecialchars($order['customer']['fullName']) ?></td>
                <td><?= htmlspecialchars($order['customer']['address']) ?></td>
                <td><?= htmlspecialchars($order['customer']['email']) ?></td>
                <td><?= htmlspecialchars($order['customer']['phone']) ?></td>
                <!-- aquí llamamos a la función que definimos arriba del todo -->
                <td><?= formatProducts($order['products']) ?></td>
                <td><?= htmlspecialchars($order['totalWithVat']) ?></td>
            </tr>

            <?php endforeach; // cierra el bucle que abrimos arriba ?>

            </table>

            <div class="botones-vuelta">
                <a class="btn-secundario" href="../client/listar.html">Back to orders menu</a>
                <a class="btn-secundario" href="../client/menu-operaciones.html">Back to operations menu</a>
                <a class="btn-secundario" href="../client/index.html">Back to home</a>
            </div>
        </div>
    </div>
    <footer>
        <p>SportMás &copy; 2026</p>
        <p>123 Sports Avenue, Barcelona, Spain</p>
        <p>contact@sportmas.example</p>
    </footer>
</body>
</html>