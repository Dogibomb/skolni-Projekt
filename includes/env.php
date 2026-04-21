<?php

function env(string $key, ?string $default = null): ?string
{
    $val = getenv($key);
    if ($val === false || $val === "") {
        return $default;
    }
    return $val;
}

