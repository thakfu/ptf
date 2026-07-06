<?php

// 7-5-26: Created new DB call to get discord hook keys   - MK

function getSecret($key)
{

    global $connection;

    static $cache = [];

    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $sql = "
        SELECT secret_value
        FROM ptf_app_secrets
        WHERE secret_key = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die(mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "s", $key);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $secretValue);

    $found = mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);

    $cache[$key] = $found ? $secretValue : null;

    return $cache[$key];
}