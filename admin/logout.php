<?php
session_start();

/* REMOVE ADMIN FLAG */
unset($_SESSION['is_admin']);

/* END SESSION */
session_destroy();

/* REMOVE COOKIE */
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

/* REDIRECT USER */
header("Location: /devinapura/");
exit;
