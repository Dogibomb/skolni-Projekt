<?php
require_once __DIR__ . "/../includes/bootstrap.php";

header("Content-Type: application/json; charset=utf-8");

// pouze admin muze videt objednavky
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    http_response_code(401);
    echo json_encode(["ok" => false, "error" => "Unauthorized"]);
    exit;
}

try {
    // tady vytahne vsechny objednavky z databaze serazene od nejnovejsi
    $stmt = db()->query("select id, user_id, name, email, product, price, status, created_at from public.orders order by created_at desc");
    $rows = $stmt->fetchAll();
    echo json_encode(["ok" => true, "orders" => $rows]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => $e->getMessage()]);
}