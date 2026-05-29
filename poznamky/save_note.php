<?php
require_once __DIR__ . "/../includes/bootstrap.php";

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: ../shop/shop.php");
    exit;
}

$action   = $_POST["action"] ?? "";
$redirect = $_POST["redirect"] ?? "notes.php";

// pridat poznamku
if ($action === "add") {
    $user_id = (int)($_POST["selected_user"] ?? 0);
    $text    = trim($_POST["text"] ?? "");

    if ($user_id > 0 && $text !== "") {
        $stmt = db()->prepare("INSERT INTO public.notes (user_id, text) VALUES (:uid, :text)");
        $stmt->execute([":uid" => $user_id, ":text" => $text]);
    }

    // vrat se zpet na notes se stejnym searchem
    $search = trim($_GET["search"] ?? $_POST["search"] ?? "");
    header("Location: notes.php" . ($search ? "?search=" . urlencode($search) : ""));
    exit;
}

// smazat poznamku
if ($action === "delete") {
    $id = (int)($_POST["id"] ?? 0);
    if ($id > 0) {
        $stmt = db()->prepare("DELETE FROM public.notes WHERE id = :id");
        $stmt->execute([":id" => $id]);
    }
    header("Location: " . $redirect);
    exit;
}

header("Location: notes.php");
exit;
