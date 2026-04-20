<?php
session_start();

$je_admin = false;

if (isset($_SESSION["is_admin"])) {
    if ($_SESSION["is_admin"] === true) {
        $je_admin = true;
    }
}

if ($je_admin === true) {
    header("Location: index/index.php");
    exit;
} else {
    header("Location: shop/shop.html");
    exit;
}
?>

