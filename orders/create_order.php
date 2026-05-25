<?php
require_once __DIR__ . "/../includes/bootstrap.php";

header("Content-Type: application/json; charset=utf-8");

// pouze POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["ok" => false, "error" => "metoda neni povolena"]);
    exit;
}

// tady zkontroluje jestli je uzivatel prihlasen
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "error" => "Nejsi přihlášený"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$product = (string)($data["product"] ?? "");
$price = (int)($data["price"] ?? 0);

if ($product === "" || $price <= 0) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "spatny vstup"]);
    exit;
}

try {
    // tady vytahne jmeno a email uzivatele z databaze
    $stmt = db()->prepare('select name, email from public."Users" where id = :id limit 1');
    $stmt->execute([":id" => $_SESSION["user_id"]]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(500);
        echo json_encode(["ok" => false, "error" => "Uživatel nenalezen"]);
        exit;
    }

    // tady vlozi objednavku do databaze vcetne jmena a emailu uzivatele
    $stmt = db()->prepare("insert into public.orders (user_id, product, price, status, name, email) values (:uid, :prod, :price, 'new', :name, :email)");
    $stmt->execute([
        ":uid" => $_SESSION["user_id"],
        ":prod" => $product,
        ":price" => $price,
        ":name" => $user["name"],
        ":email" => $user["email"],
    ]);

    echo json_encode(["ok" => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => $e->getMessage()]);
}