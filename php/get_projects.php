<?php
require_once 'config.php';
setCorsHeaders();
header('Content-Type: application/json');

$conn = getConnection();
$result = $conn->query(
    'SELECT id, title, description, tech_stack, category, image_url, github_url, live_url, status
     FROM projects ORDER BY created_at DESC'
);

$projects = [];
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

$conn->close();
jsonResponse(true, '', ['projects' => $projects]);
