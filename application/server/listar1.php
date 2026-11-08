<?php

// Rutas al directorio y al archivo donde se guardan todas las comandas
const ORDERS_DIR  = __DIR__ . '/../../orders';
const ORDERS_FILE = ORDERS_DIR . '/orders.json';

// Comprobación 1: ¿existe el archivo de comandas?
// Si no existe, no hay nada que buscar: error 404 de archivo
if (!file_exists(ORDERS_FILE)) {

    http_response_code(404);

    echo '
    <h2>Error: orders file not found</h2>

    <a href="../client/listar.html"><button>Back</button></a>
    <a href="../client/menu-operaciones.html"><button>Operations</button></a>
    <a href="../client/index.html"><button>Home</button></a>
    ';

    exit;   // detiene el script, no se ejecuta nada más
}

// El código de la comanda llega por GET, en la URL: viewOrder.php?orderId=123
// El "?? ''" significa: si no viene orderId, usa un texto vacío en su lugar
$orderId = $_GET['orderId'] ?? '';

// Lee el archivo y lo convierte de texto JSON a array asociativo de PHP
$orders = json_decode(file_get_contents(ORDERS_FILE), true);

// Aquí guardaremos la comanda si la encontramos; empieza en null (no encontrada)
$orderFound = null;

// Recorre todas las comandas una a una, comparando su orderId con el buscado
foreach ($orders as $order) {

    if ($order['orderId'] === $orderId) {
        $orderFound = $order;   // la hemos encontrado: la guardamos
        break;                  // y paramos el bucle, ya no hace falta seguir mirando
    }
}

// Comprobación 2: ¿se ha encontrado la comanda?
// Si sigue siendo null, es que no existe ninguna con ese orderId: error 404 de comanda
// (distinto del error anterior, que era de archivo no encontrado)
if ($orderFound === null) {

    http_response_code(404);

    echo '
    <h2>Error: order not found</h2>

    <a href="../client/listar.html"><button>Back</button></a>
    <a href="../client/menu-operaciones.html"><button>Operations</button></a>
    <a href="../client/index.html"><button>Home</button></a>
    ';

    exit;
}

// Si llegamos hasta aquí, todo ha ido bien: tenemos la comanda y respondemos 200
http_response_code(200);

// A partir de aquí empieza el HTML que muestra la comanda encontrada
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order details</title>
    <!-- Mismo style.css que el resto de páginas, con las clases .resultado-card, etc. -->
    <link rel="stylesheet" href="../client/style.css">
</head>
<body>
    <nav>
        <img class="logo" src="../client/img/logo.png">
        <!-- ../client/ porque este .php vive en la carpeta "server", no en "client" -->
        <a href="../client/index.html">Start</a>
        <a href="../client/documentacion.html">Documentation</a>
        <a href="../client/login.html">Log in</a>
        <a href="../client/menu-operaciones.html"><img class="flecha" src="../client/img/flecha.png"></a>
    </nav>

    <div class="resultado-container">
        <div class="resultado-card">
            <h1>Order details</h1>

            <!-- Primera tabla: datos generales de la comanda.
                 Aquí NO hay foreach, porque solo hay UNA comanda que mostrar -->
            <table>

            <tr>
                <th>Order ID</th>
                <!-- htmlspecialchars evita que datos con <, >, " rompan el HTML -->
                <td><?= htmlspecialchars($orderFound['orderId']) ?></td>
            </tr>

            <tr>
                <th>Customer</th>
                <td><?= htmlspecialchars($orderFound['customer']['fullName']) ?></td>
            </tr>

            <tr>
                <th>Address</th>
                <td><?= htmlspecialchars($orderFound['customer']['address']) ?></td>
            </tr>

            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($orderFound['customer']['email']) ?></td>
            </tr>

            <tr>
                <th>Phone</th>
                <td><?= htmlspecialchars($orderFound['customer']['phone']) ?></td>
            </tr>

            <tr>
                <th>Total with VAT</th>
                <td><?= htmlspecialchars($orderFound['totalWithVat']) ?> €</td>
            </tr>

            </table>

            <h2>Products</h2>

            <!-- Segunda tabla: lista de productos de la comanda.
                 Aquí SÍ hace falta foreach, porque una comanda puede
                 tener varios productos distintos -->
            <table>

            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>

            <?php
            // Recorre cada producto dentro de $orderFound['products']
            // y genera una fila de tabla por cada uno
            foreach ($orderFound['products'] as $product): ?>

            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['price']) ?> €</td>
                <td><?= htmlspecialchars($product['quantity']) ?></td>
            </tr>

            <?php endforeach; ?>

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