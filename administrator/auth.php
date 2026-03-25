<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
    header("Location: ../?login=required");
    exit();
}

$timeout_duration = 1800;

if (
    isset($_SESSION['LAST_ACTIVITY']) &&
    (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration
) {
    session_unset();
    session_destroy();
    header("Location: ../?timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
