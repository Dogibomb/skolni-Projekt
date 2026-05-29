<?php
require_once __DIR__ . "/../includes/bootstrap.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    http_response_code(401);
    echo json_encode(["ok" => false, "error" => "Unauthorized"]);
    exit;
}

try {
    $search = trim($_GET["search"] ?? "");

    // jenom zakaznici kteri maji aspon jednu objednavku
    if ($search !== "") {
        $stmtCustomers = db()->prepare('
            SELECT DISTINCT u.id, u.name, u.email
            FROM public."Users" u
            INNER JOIN public.orders o ON o.user_id = u.id
            WHERE u.name != \'admin\'
              AND (LOWER(u.name) LIKE LOWER(:search) OR LOWER(u.email) LIKE LOWER(:search))
            ORDER BY u.name
        ');
        $stmtCustomers->execute([":search" => "%" . $search . "%"]);
    } else {
        $stmtCustomers = db()->query('
            SELECT DISTINCT u.id, u.name, u.email
            FROM public."Users" u
            INNER JOIN public.orders o ON o.user_id = u.id
            WHERE u.name != \'admin\'
            ORDER BY u.name
        ');
    }
    $customers = $stmtCustomers->fetchAll();

    // vsechny poznamky z databaze
    $stmtNotes = db()->query('
        SELECT n.id, n.user_id, n.text, n.created_at, u.name as customer_name, u.email as customer_email
        FROM public.notes n
        INNER JOIN public."Users" u ON u.id = n.user_id
        ORDER BY n.created_at DESC
    ');
    $notes = $stmtNotes->fetchAll();

    echo json_encode([
        "ok" => true,
        "customers" => $customers,
        "notes"     => $notes,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => $e->getMessage()]);
}
