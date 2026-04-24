<?php

require_once __DIR__ . "/supabase_db.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = supabase_pdo();
    }
    return $pdo;
}

