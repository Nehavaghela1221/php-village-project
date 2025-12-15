<?php
session_start();

if (($_POST['pin'] ?? '') === '1234') {
    $_SESSION['is_admin'] = true;
    echo 'success';
} else {
    echo 'fail';
}
