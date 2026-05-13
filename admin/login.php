<?php
ob_start();
session_start();
require_once '../php/config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    ob_end_clean();
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $conn = getConnection();
        $stmt = $conn->prepare('SELECT id, username, password FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $stmt->close(); $conn->close();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $admin['id'];
            $_SESSION['admin_username']  = $admin['username'];
            header('Location: dashboard.php'); exit;
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#F5F2ED;padding:2rem}

.form-card{
  width:100%;max-width:400px;
  background:#ffffff;
  border:1px solid #D5D2CB;
  border-radius:16px;
  padding:2.5rem;
  box-shadow:0 4px 24px rgba(30,28,26,.06);
}
.form-card-title{font-size:1.4rem;font-weight:800;color:#2A2520;margin-bottom:.35rem}
.form-card-sub{font-size:.82rem;color:#8A847C;margin-bottom:2rem}

.hint{
  display:flex;align-items:center;gap:.6rem;
  background:rgba(197,199,188,.25);border:1px solid #C5C7BC;
  border-radius:8px;padding:.65rem .875rem;
  font-family:'JetBrains Mono',monospace;font-size:.73rem;color:#5C5650;
  margin-bottom:1.5rem;
}
.hint svg{width:13px;height:13px;color:#9A9186;flex-shrink:0}

.error-msg{
  display:flex;align-items:center;gap:.6rem;
  background:rgba(154,145,134,.12);border:1px solid #C5C7BC;
  border-radius:8px;padding:.65rem .875rem;
  font-size:.82rem;color:#5C5650;margin-bottom:1.2rem;
}
.error-msg svg{width:14px;height:14px;flex-shrink:0}

.field{margin-bottom:1.1rem}
.field label{
  display:block;font-size:.68rem;font-weight:700;
  letter-spacing:.1em;text-transform:uppercase;
  color:#8A847C;margin-bottom:.45rem;
}
.field input{
  width:100%;background:#EEECEA;border:1.5px solid #C5C7BC;
  border-radius:9px;padding:.75rem 1rem;
  font-family:'Inter',sans-serif;font-size:.9rem;color:#2A2520;
  outline:none;transition:border-color .2s,box-shadow .2s;
}
.field input:focus{border-color:#9A9186;box-shadow:0 0 0 3px rgba(154,145,134,.12)}
.field input::placeholder{color:#B6AE9F}
.pw-wrap{position:relative}
.pw-wrap input{padding-right:2.5rem}
.eye{
  position:absolute;right:.75rem;top:50%;transform:translateY(-50%);
  background:none;border:none;cursor:pointer;
  color:#B6AE9F;display:flex;padding:4px;transition:color .2s;
}
.eye:hover{color:#5C5650}
.eye svg{width:16px;height:16px}

.submit{
  width:100%;margin-top:1.5rem;padding:.9rem;
  background:#2A2520;color:#FBF3D1;
  border:none;border-radius:9px;
  font-family:'Inter',sans-serif;font-size:.92rem;font-weight:700;
  cursor:pointer;transition:all .2s;
  display:flex;align-items:center;justify-content:center;gap:.5rem;
}
.submit:hover{background:#5C5650;transform:translateY(-1px)}
.submit:active{transform:translateY(0)}
.submit svg{width:15px;height:15px}

.back{
  display:inline-flex;align-items:center;gap:.35rem;
  font-size:.75rem;color:#B6AE9F;text-decoration:none;
  margin-top:1.5rem;transition:color .2s;
}
.back:hover{color:#2A2520}
.back svg{width:13px;height:13px}

@media(max-width:768px){
  body{grid-template-columns:1fr}
  .left{display:none}
  .right{background:#EEECEA;padding:2rem}
}
</style>
</head>
<body>

<div class="form-card">
    <h2 class="form-card-title">Welcome back</h2>
    <p class="form-card-sub">Sign in to your admin panel</p>

    <?php if ($error): ?>
    <div class="error-msg">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST">
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" placeholder="admin"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               autocomplete="username" autofocus>
      </div>
      <div class="field">
        <label>Password</label>
        <div class="pw-wrap">
          <input type="password" name="password" id="pw" placeholder="••••••••" autocomplete="current-password">
          <button type="button" class="eye" onclick="togglePw()">
            <svg id="eyeIco" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </button>
        </div>
      </div>
      <button type="submit" class="submit">
        Sign In
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </button>
    </form>

    <a href="../index.html" class="back">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to portfolio
    </a>
  </div>

<script>
function togglePw(){
  const p=document.getElementById('pw');
  p.type=p.type==='password'?'text':'password';
}
</script>
</body>
</html>
