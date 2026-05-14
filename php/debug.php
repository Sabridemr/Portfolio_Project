<?php
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "DB_NAME: " . (getenv('DB_NAME') ?: 'NOT SET') . "\n";
echo "DB_USER: " . (getenv('DB_USER') ?: 'NOT SET') . "\n";
echo "DB_PORT: " . (getenv('DB_PORT') ?: 'NOT SET') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? 'SET' : 'NOT SET') . "\n";
