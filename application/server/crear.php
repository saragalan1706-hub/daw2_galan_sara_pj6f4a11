<?php
const VAT_RATE    = 0.21;
const ORDERS_DIR  = __DIR__ . '/../../orders';
const ORDERS_FILE = ORDERS_DIR . '/orders.json';

// La "caja registradora": los precios viven en el servidor
function getProducts(): array {
    return [
        "ball"   => ["name" => "Football Ball", "price" => 24.99],
        "shoes"  => ["name" => "Running shoes", "price" => 79.90],
        "racket" => ["name" => "Tennis racket", "price" => 59.50],
        "mat"    => ["name" => "Yoga mat",      "price" => 19.95],
    ];
}

// Devuelve solo los productos con cantidad > 0
function getSelectedProducts(array $quantities): array {
    $products = getProducts();
    $selected = [];
    foreach ($quantities as $id => $qty) {
        $qty = (int)$qty;
        if (isset($products[$id]) && $qty > 0) {
            $selected[$id] = [
                "name"     => $products[$id]["name"],
                "price"    => $products[$id]["price"],
                "quantity" => $qty,
            ];
        }
    }
    return $selected;
}

function calculateTotalWithVat(array $selected): float {
    $subtotal = 0;
    foreach ($selected as $item) {
        $subtotal += $item["price"] * $item["quantity"];
    }
    return round($subtotal * (1 + VAT_RATE), 2);
}

// null = no se puede abrir; [] = aún no existe (se crea con el primer pedido)
function loadOrders(): ?array {
    if (!is_dir(ORDERS_DIR)) return null; // Si no se crea el archivo da errro
    if (!file_exists(ORDERS_FILE)) return [];
    $data = json_decode(file_get_contents(ORDERS_FILE), true);
    return is_array($data) ? $data : null;
}

function saveOrders(array $orders): bool {
    return file_put_contents(ORDERS_FILE, json_encode($orders, JSON_PRETTY_PRINT)) !== false;
}

function sendFileNotFound(): void {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Error</title></head><body>
        <p>Error: the orders file could not be found or opened.</p>
        <a href="../client/listar.html"><button>Back to orders menu</button></a>
        <a href="../client/menu-operaciones.html"><button>Back to operations menu</button></a>
        <a href="../client/index.html"><button>Back to home</button></a>
    </body></html>';
    exit;
}

function createOrder(array $post): float {
    $orders = loadOrders();
    if ($orders === null) sendFileNotFound();

    $selected = getSelectedProducts($post['quantity'] ?? []);
    $total    = calculateTotalWithVat($selected);

    $orders[] = [
        "orderId"  => $post['orderId'] ?? '',
        "customer" => [
            "fullName" => $post['fullName'] ?? '',
            "address"  => $post['address'] ?? '',
            "email"    => $post['email'] ?? '',
            "phone"    => $post['phone'] ?? '',
        ],
        "products"     => $selected,
        "totalWithVat" => $total,
    ];

    if (!saveOrders($orders)) sendFileNotFound();
    return $total;
}

// ---- Programa principal ----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$total = createOrder($_POST);
http_response_code(200);
header('Content-Type: text/plain; charset=UTF-8');
echo number_format($total, 2, '.', '');   // solo el número, sin HTML