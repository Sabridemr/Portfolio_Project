<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    jsonResponse(false, 'Unauthorized.');
}

$action = $_POST['action'] ?? '';
$conn   = getConnection();

switch ($action) {

    case 'add':
        $title  = trim(htmlspecialchars($_POST['title']       ?? ''));
        $desc   = trim(htmlspecialchars($_POST['description'] ?? ''));
        $tech   = trim(htmlspecialchars($_POST['tech_stack']  ?? ''));
        $cat    = trim(htmlspecialchars($_POST['category']    ?? 'fullstack'));
        $github = trim(htmlspecialchars($_POST['github_url']  ?? ''));
        $live   = trim(htmlspecialchars($_POST['live_url']    ?? ''));
        $img    = trim(htmlspecialchars($_POST['image_url']   ?? ''));
        $status = trim(htmlspecialchars($_POST['status']      ?? 'live'));

        if (!$title || !$desc) jsonResponse(false, 'Title and description are required.');

        $stmt = $conn->prepare(
            'INSERT INTO projects (title, description, tech_stack, category, github_url, live_url, image_url, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->bind_param('ssssssss', $title, $desc, $tech, $cat, $github, $live, $img, $status);
        $ok = $stmt->execute();
        $stmt->close();
        jsonResponse($ok, $ok ? 'Project added successfully.' : 'Failed to add project.');
        break;

    case 'edit':
        $id     = (int)($_POST['id'] ?? 0);
        $title  = trim(htmlspecialchars($_POST['title']       ?? ''));
        $desc   = trim(htmlspecialchars($_POST['description'] ?? ''));
        $tech   = trim(htmlspecialchars($_POST['tech_stack']  ?? ''));
        $cat    = trim(htmlspecialchars($_POST['category']    ?? ''));
        $github = trim(htmlspecialchars($_POST['github_url']  ?? ''));
        $live   = trim(htmlspecialchars($_POST['live_url']    ?? ''));
        $img    = trim(htmlspecialchars($_POST['image_url']   ?? ''));
        $status = trim(htmlspecialchars($_POST['status']      ?? 'live'));

        $stmt = $conn->prepare(
            'UPDATE projects SET title=?, description=?, tech_stack=?, category=?,
             github_url=?, live_url=?, image_url=?, status=? WHERE id=?'
        );
        $stmt->bind_param('ssssssssi', $title, $desc, $tech, $cat, $github, $live, $img, $status, $id);
        $ok = $stmt->execute();
        $stmt->close();
        jsonResponse($ok, $ok ? 'Project updated successfully.' : 'Failed to update.');
        break;

    case 'delete':
        $id   = (int)($_POST['id'] ?? 0);
        $stmt = $conn->prepare('DELETE FROM projects WHERE id=?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        jsonResponse($ok, $ok ? 'Project deleted.' : 'Failed to delete.');
        break;

    default:
        jsonResponse(false, 'Unknown action.');
}
$conn->close();
