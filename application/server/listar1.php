<?php

const ORDERS_DIR  = __DIR__ . '/../../orders';
const ORDERS_FILE = ORDERS_DIR . '/orders.json';

if (!file_exists(ORDERS_FILE)) {

    http_response_code(404);

    echo '
    <h2>Error: orders file not found</h2>

    <a href="../client/listar.html"><button>Back</button></a>
    <a href="../client/menu-operaciones.html"><button>Operations</button></a>
    <a href="../client/index.html"><button>Home</button></a>
    ';

    exit;
}

$orderId = $_GET['orderId'] ?? '';

$orders = json_decode(file_get_contents(ORDERS_FILE), true);

$orderFound = null;

foreach ($orders as $order) {

    if ($order['orderId'] === $orderId) {
        $orderFound = $order;
        break;
    }
}

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

http_response_code(200);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order details</title>
    <link rel="stylesheet" href="../client/style.css">
</head>
<body>
    <nav>
        <img class="logo" src="../client/img/logo.png">
        <a href="index.html">Start</a>
        <a href="documentacion.html">Documentation</a>
        <a href="login.html">Log in</a>
        <a href="../client/menu-operaciones.html"><img class="flecha" src="../client/img/flecha.png"></a>
    </nav>

<h1>Order details</h1>

<table border="1">

<tr>
    <th>Order ID</th>
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

<table border="1">

<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
</tr>

<?php foreach ($orderFound['products'] as $product): ?>

<tr>
    <td><?= htmlspecialchars($product['name']) ?></td>
    <td><?= htmlspecialchars($product['price']) ?> €</td>
    <td><?= htmlspecialchars($product['quantity']) ?></td>
</tr>

<?php endforeach; ?>

</table>

<br>

<a href="../client/listar.html">
    <button>Back to orders menu</button>
</a>

<a href="../client/menu-operaciones.html">
    <button>Back to operations menu</button>
</a>

<a href="../client/index.html">
    <button>Back to home</button>
</a>

</body>
</html>