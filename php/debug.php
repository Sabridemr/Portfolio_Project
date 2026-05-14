<?php
$dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: '';
if ($dbUrl) {
    $p = parse_url($dbUrl);
    echo "SOURCE: DATABASE_URL\n";
    echo "HOST: " . ($p['host'] ?? 'N/A') . "\n";
    echo "PORT: " . ($p['port'] ?? 'N/A') . "\n";
    echo "USER: " . ($p['user'] ?? 'N/A') . "\n";
    echo "NAME: " . ltrim($p['path'] ?? '', '/') . "\n";
} else {
    echo "SOURCE: env vars\n";
    echo "HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
    echo "PORT: " . (getenv('DB_PORT') ?: 'NOT SET') . "\n";
    echo "USER: " . (getenv('DB_USER') ?: 'NOT SET') . "\n";
    echo "NAME: " . (getenv('DB_NAME') ?: 'NOT SET') . "\n";
}
echo "DATABASE_URL set: " . ($dbUrl ? 'YES' : 'NO') . "\n";
