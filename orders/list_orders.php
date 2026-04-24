<?php

require_once __DIR__ . "/../includes/bootstrap.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    http_response_code(401);
    echo json_encode(["ok" => false, "error" => "Unauthorized"]);
    exit;
}

try {
    $stmt = db()->query("select id, user_id, product, price, status, created_at from public.orders order by created_at desc");
    $rows = $stmt->fetchAll();
    echo json_encode(["ok" => true, "orders" => $rows]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => $e->getMessage()]);
}

