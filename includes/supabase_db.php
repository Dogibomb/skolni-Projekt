<?php

require_once __DIR__ . "/env.php";

function supabase_pdo(): PDO
{
    $host = env("SUPABASE_DB_HOST", "aws-0-eu-central-1.pooler.supabase.com");
    $port = env("SUPABASE_DB_PORT", "6543");
    $db = env("SUPABASE_DB_NAME", "postgres");
    $user = env("SUPABASE_DB_USER", "postgres.lsbxkkttofyozlqzvkhe");
    $pass = env("SUPABASE_DB_PASSWORD", "skolniprojekt");
    $sslmode = env("SUPABASE_DB_SSLMODE", "require");

    if ($host === null || $pass === null) {
        throw new RuntimeException("Missing Supabase DB environment variables (SUPABASE_DB_HOST / SUPABASE_DB_PASSWORD).");
    }

    $dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode={$sslmode}";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

