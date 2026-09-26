<?php
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function getCsrfToken() {
    return generateCsrfToken();
}

function csrfInput() {
    return '<input type="hidden" name="csrf_token" value="' . getCsrfToken() . '">';
}

function verifyCsrfToken($token) {
    return isset($token) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
