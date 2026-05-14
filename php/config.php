<?php
// ── Load .env file into environment (only if key not already set) ─
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $val] = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        if (getenv($key) === false) {
            putenv("$key=$val");
            $_ENV[$key] = $val;
        }
    }
}

// ── Database Configuration ────────────────────────────────────
define('DB_HOST',    getenv('DB_HOST')     ?: '127.0.0.1');
define('DB_PORT',    getenv('DB_PORT')     ?: '3306');
define('DB_USER',    getenv('DB_USER')     ?: 'root');
define('DB_PASS',    getenv('DB_PASSWORD') ?: 'root123');
define('DB_NAME',    getenv('DB_NAME')     ?: 'portfolio_db');
define('DB_CHARSET', 'utf8mb4');

// ── Connection ────────────────────────────────────────────────
function getConnection(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, (int) DB_PORT);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }
    $conn->set_charset(DB_CHARSET);
    return $conn;
}

// ── Helpers ───────────────────────────────────────────────────
function jsonResponse(bool $success, string $message = '', array $data = []): void {
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}

function setCorsHeaders(): void {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
}
