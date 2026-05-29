<?php
require_once __DIR__ . "/../includes/bootstrap.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    http_response_code(401);
    echo json_encode(["ok" => false, "error" => "Unauthorized"]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];

// POST = přidat poznámku
if ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = (int)($data["user_id"] ?? 0);
    $text    = trim((string)($data["text"] ?? ""));

    if ($user_id <= 0 || $text === "") {
        http_response_code(400);
        echo json_encode(["ok" => false, "error" => "Chybějící data"]);
        exit;
    }

    try {
        $stmt = db()->prepare("INSERT INTO public.notes (user_id, text) VALUES (:uid, :text) RETURNING id, created_at");
        $stmt->execute([":uid" => $user_id, ":text" => $text]);
        $row = $stmt->fetch();
        echo json_encode(["ok" => true, "id" => $row["id"], "created_at" => $row["created_at"]]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }
    exit;
}

// DELETE = smazat poznámku
if ($method === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = (int)($data["id"] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["ok" => false, "error" => "Chybí ID"]);
        exit;
    }

    try {
        $stmt = db()->prepare("DELETE FROM public.notes WHERE id = :id");
        $stmt->execute([":id" => $id]);
        echo json_encode(["ok" => true]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["ok" => false, "error" => "Metoda není povolena"]);
