<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Clean URL → redirect
$redirects = [
    '/login'     => '/admin/login.php',
    '/admin'     => '/admin/dashboard.php',
    '/dashboard' => '/admin/dashboard.php',
];

if (isset($redirects[$uri])) {
    header('Location: ' . $redirects[$uri], true, 301);
    exit;
}

// Let built-in server handle everything else
return false;
