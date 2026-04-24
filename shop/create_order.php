<?php

require_once __DIR__ . "/../includes/bootstrap.php";

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["ok" => false, "error" => "metoda neni povolena"]);
    exit;
}

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

$product = isset($data["product"]) ? (string)$data["product"] : "";
$price = isset($data["price"]) ? (int)$data["price"] : 0;

if ($product === "" || $price <= 0) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "spatny vstup"]);
    exit;
}

try {
    $userId = $_SESSION["user_id"] ?? null;

    if ($userId === null) {
        $stmt = db()->query('select id from public."Users" where name = \'admin\' limit 1');
        $admin = $stmt->fetch();
        if (!$admin) {
            http_response_code(500);
            echo json_encode(["ok" => false, "error" => "admin neexistuje v tabulce"]);
            exit;
        }
        $userId = $admin["id"];
    }

    $stmt = db()->prepare("insert into public.orders (user_id, product, price, status) values (:uid, :prod, :price, 'new')");
    $stmt->execute([
        ":uid" => $userId,
        ":prod" => $product,
        ":price" => $price,
    ]);

    echo json_encode(["ok" => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => $e->getMessage()]);
}