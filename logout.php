<?php
session_start();
if (isset($_SESSION['admin'])) {
    unset($_SESSION['admin']);
    unset($_SESSION['csrf_token']);
}
session_destroy();
header('Location: login.php');
exit;
?>
