<?php

const ORDERS_DIR  = __DIR__ . '/../../orders';
const ORDERS_FILE = ORDERS_DIR . '/orders.json';

// Convierte el array de productos de un pedido en un texto legible,
// tipo "Football Ball x2 (24.99 €), Yoga mat x1 (19.95 €)"
function formatProducts(array $products): string {
    $items = [];
    foreach ($products as $product) {
        $items[] = htmlspecialchars($product['name'])
                 . ' x' . $product['quantity']
                 . ' (' . number_format($product['price'], 2) . ' €)';
    }
    return implode('<br>', $items);   // uno por línea dentro de la celda
}

if (!file_exists(ORDERS_FILE)) {

    http_response_code(404);

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

    exit;
}

$orders = json_decode(file_get_contents(ORDERS_FILE), true);

http_response_code(200);
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
        <!-- faltaba ../client/ en estos tres enlaces, porque este PHP vive en server -->
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

            <?php foreach($orders as $order): ?>

            <tr>
                <td><?= htmlspecialchars($order['orderId']) ?></td>
                <td><?= htmlspecialchars($order['customer']['fullName']) ?></td>
                <td><?= htmlspecialchars($order['customer']['address']) ?></td>
                <td><?= htmlspecialchars($order['customer']['email']) ?></td>
                <td><?= htmlspecialchars($order['customer']['phone']) ?></td>
                <td><?= formatProducts($order['products']) ?></td>
                <td><?= htmlspecialchars($order['totalWithVat']) ?></td>
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