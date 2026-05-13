<?php
ob_start();
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    ob_end_clean();
    exit;
}

require_once '../php/config.php';

$conn = getConnection();

$projects = [];
$r = $conn->query('SELECT * FROM projects ORDER BY created_at DESC');
while ($row = $r->fetch_assoc()) $projects[] = $row;

$contacts = [];
$r = $conn->query('SELECT * FROM contacts ORDER BY created_at DESC LIMIT 50');
while ($row = $r->fetch_assoc()) $contacts[] = $row;

$conn->close();

$total   = count($projects);
$live    = count(array_filter($projects, fn($p)=>($p['status']??'')===('live')));
$msgs    = count($contacts);
$user    = $_SESSION['admin_username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Inter',system-ui,sans-serif;background:#EEECEA;color:#2A2520;display:flex;min-height:100vh}

/* ── Sidebar ───────────────────────────────────────────── */
.sb{
  width:220px;background:#2A2520;
  display:flex;flex-direction:column;
  position:fixed;top:0;left:0;bottom:0;z-index:200;
  flex-shrink:0;
}
.sb-top{padding:1.5rem 1.25rem;border-bottom:1px solid rgba(255,255,255,.06)}
.sb-logo{display:flex;align-items:center;gap:.6rem;margin-bottom:0}
.sb-logo-box{
  width:34px;height:34px;background:#FBF3D1;border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  font-family:'JetBrains Mono',monospace;font-weight:700;font-size:.78rem;color:#2A2520;
  flex-shrink:0;
}
.sb-logo-name{font-size:.85rem;font-weight:700;color:#FBF3D1;line-height:1.1}
.sb-logo-sub{font-size:.65rem;color:rgba(255,255,255,.25);margin-top:1px}

.sb-nav{flex:1;padding:1rem .75rem;overflow-y:auto}
.sb-sect{font-size:.6rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.2);padding:.75rem .6rem .35rem;margin-top:.25rem}
.sb-btn{
  display:flex;align-items:center;gap:.6rem;
  width:100%;padding:.6rem .75rem;
  border-radius:8px;border:none;background:none;
  font-family:'Inter',sans-serif;font-size:.82rem;font-weight:500;
  color:rgba(255,255,255,.45);cursor:pointer;text-decoration:none;
  transition:all .18s;text-align:left;
}
.sb-btn svg{width:15px;height:15px;flex-shrink:0}
.sb-btn:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.8)}
.sb-btn.on{background:rgba(251,243,209,.1);color:#FBF3D1;font-weight:600}
.sb-btn.red{color:rgba(182,174,159,.45)}
.sb-btn.red:hover{background:rgba(182,174,159,.08);color:rgba(255,255,255,.65)}
.sb-badge{margin-left:auto;font-size:.64rem;padding:1px 7px;border-radius:50px;background:rgba(255,255,255,.08);color:rgba(255,255,255,.35)}

.sb-foot{padding:.875rem 1.25rem;border-top:1px solid rgba(255,255,255,.06)}
.sb-user{display:flex;align-items:center;gap:.65rem}
.sb-avatar{
  width:30px;height:30px;border-radius:7px;
  background:rgba(251,243,209,.12);
  display:flex;align-items:center;justify-content:center;
  font-weight:700;font-size:.75rem;color:#FBF3D1;
  font-family:'JetBrains Mono',monospace;flex-shrink:0;
}
.sb-uname{font-size:.8rem;font-weight:600;color:#FBF3D1}
.sb-urole{font-size:.65rem;color:rgba(255,255,255,.25)}

/* ── Main ──────────────────────────────────────────────── */
.main{margin-left:220px;flex:1;display:flex;flex-direction:column;min-height:100vh}

/* Topbar */
.topbar{
  background:#F5F4F1;border-bottom:1px solid #C5C7BC;
  height:56px;padding:0 1.75rem;
  display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:100;
}
.tb-title{font-size:.9rem;font-weight:700}
.tb-right{display:flex;align-items:center;gap:.6rem}

/* Content */
.content{padding:1.75rem;flex:1}

/* Tab panes */
.pane{display:none}.pane.on{display:block}

/* ── Buttons ── */
.btn{
  display:inline-flex;align-items:center;gap:.4rem;
  padding:.45rem .9rem;border-radius:8px;border:none;
  font-family:'Inter',sans-serif;font-size:.78rem;font-weight:600;
  cursor:pointer;transition:all .18s;
}
.btn svg{width:13px;height:13px}
.btn-dark{background:#2A2520;color:#FBF3D1}
.btn-dark:hover{background:#5C5650}
.btn-outline{background:transparent;border:1px solid #C5C7BC;color:#5C5650}
.btn-outline:hover{border-color:#9A9186;color:#2A2520}
.btn-ghost{background:rgba(197,199,188,.2);border:1px solid #C5C7BC;color:#5C5650}
.btn-ghost:hover{background:rgba(182,174,159,.25)}
.btn-sm{padding:.32rem .7rem;font-size:.72rem}
.btn-del{background:rgba(154,145,134,.1);border:1px solid #C5C7BC;color:#9A9186}
.btn-del:hover{background:rgba(154,145,134,.2);color:#5C5650}

/* ── Stat cards ── */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem}
.stat{
  background:#F5F4F1;border:1px solid #C5C7BC;border-radius:12px;
  padding:1.1rem 1.25rem;display:flex;flex-direction:column;gap:.75rem;
}
.stat-top{display:flex;align-items:center;justify-content:space-between}
.stat-icon{
  width:36px;height:36px;border-radius:9px;
  display:flex;align-items:center;justify-content:center;
}
.stat-icon svg{width:17px;height:17px}
.si-a{background:#2A2520;color:#FBF3D1}
.si-b{background:rgba(182,174,159,.18);color:#9A9186}
.si-c{background:rgba(197,199,188,.3);color:#5C5650}
.si-d{background:rgba(220,220,209,.4);color:#8A847C}
.stat-num{font-size:1.9rem;font-weight:800;line-height:1;letter-spacing:-.02em}
.stat-lbl{font-size:.68rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#8A847C}

/* ── Panel ── */
.panel{background:#F5F4F1;border:1px solid #C5C7BC;border-radius:12px;overflow:hidden;margin-bottom:1.25rem}
.ph{
  padding:.9rem 1.25rem;border-bottom:1px solid #DEDED1;
  display:flex;align-items:center;justify-content:space-between;
}
.ph h3{font-size:.85rem;font-weight:700}
.ph-count{font-size:.72rem;font-weight:400;color:#8A847C}

/* ── Table ── */
table{width:100%;border-collapse:collapse}
th,td{padding:.8rem 1.25rem;text-align:left;border-bottom:1px solid #DEDED1;font-size:.8rem}
th{font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#8A847C;background:#EEECEA}
tr:last-child td{border-bottom:none}
tr:hover td{background:rgba(197,199,188,.1)}
td{color:#5C5650}
td strong{color:#2A2520;font-weight:600}
.no-data{text-align:center;color:#8A847C;padding:2.5rem!important;font-size:.85rem}

/* Badges */
.bdg{display:inline-block;padding:2px 10px;border-radius:50px;font-size:.65rem;font-weight:700;letter-spacing:.04em;border:1px solid}
.bdg-live{color:#5C5650;border-color:#B6AE9F;background:rgba(182,174,159,.1)}
.bdg-dev{color:#8A847C;border-color:#C5C7BC;background:rgba(197,199,188,.12)}
.bdg-fs{color:#2A2520;border-color:#9A9186}
.bdg-fe{color:#5C5650;border-color:#B6AE9F}
.bdg-be{color:#8A847C;border-color:#C5C7BC}

.act-row{display:flex;gap:.35rem}

/* Feedback */
.fb{padding:.65rem 1rem;border-radius:8px;font-size:.8rem;display:none;margin-bottom:1rem}
.fb.show{display:block}
.fb.ok{background:rgba(197,199,188,.25);color:#5C5650;border:1px solid #C5C7BC}
.fb.err{background:rgba(154,145,134,.15);color:#9A9186;border:1px solid #C5C7BC}

/* ── Message cards ── */
.msgs{padding:1.25rem;display:flex;flex-direction:column;gap:.75rem}
.mc{background:#EEECEA;border:1px solid #C5C7BC;border-radius:10px;padding:1rem 1.25rem}
.mc-hd{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.5rem}
.mc-who{font-weight:700;font-size:.85rem}
.mc-mail{font-size:.72rem;font-weight:400;color:#8A847C}
.mc-date{font-family:'JetBrains Mono',monospace;font-size:.68rem;color:#B6AE9F;white-space:nowrap}
.mc-sub{font-size:.78rem;color:#5C5650;margin-bottom:.4rem;font-weight:500}
.mc-body{font-size:.78rem;color:#8A847C;line-height:1.65}

/* ── Modal ── */
.overlay{position:fixed;inset:0;background:rgba(42,37,32,.4);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;z-index:1000}
.overlay.open{display:flex}
.modal{
  background:#F5F4F1;border:1px solid #C5C7BC;border-radius:14px;
  padding:1.75rem;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;
  box-shadow:0 24px 64px rgba(42,37,32,.14);
}
.modal-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem}
.modal-hd h3{font-size:.95rem;font-weight:800}
.mc-btn{background:none;border:none;cursor:pointer;color:#B6AE9F;font-size:1rem;width:26px;height:26px;border-radius:6px;display:flex;align-items:center;justify-content:center;transition:all .18s}
.mc-btn:hover{background:#DEDED1;color:#2A2520}

.fg{margin-bottom:1rem}
.fg label{display:block;font-size:.64rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#8A847C;margin-bottom:.4rem}
.fg input,.fg textarea,.fg select{
  width:100%;background:#EEECEA;border:1.5px solid #C5C7BC;border-radius:8px;
  padding:.65rem .875rem;font-family:'Inter',sans-serif;font-size:.85rem;color:#2A2520;
  outline:none;transition:border-color .2s;
}
.fg input:focus,.fg textarea:focus,.fg select:focus{border-color:#9A9186}
.fg textarea{resize:vertical;min-height:80px}
.fg2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.modal-foot{display:flex;gap:.6rem;margin-top:1.25rem}

@media(max-width:900px){
  .sb{transform:translateX(-100%);transition:transform .3s}
  .sb.open{transform:translateX(0)}
  .main{margin-left:0}
  .stats{grid-template-columns:1fr 1fr}
  .topbar{padding:0 1rem}
}
@media(max-width:480px){.stats{grid-template-columns:1fr}}
</style>
</head>
<body>

<aside class="sb" id="sidebar">
  <div class="sb-top">
    <div class="sb-logo">
      <div class="sb-logo-box">SD</div>
      <div><div class="sb-logo-name">Sabri Demir</div><div class="sb-logo-sub">Admin Panel</div></div>
    </div>
  </div>

  <nav class="sb-nav">
    <div class="sb-sect">Menu</div>
    <button class="sb-btn on" id="nav-overview" onclick="go('overview')">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Overview
    </button>
    <button class="sb-btn" id="nav-projects" onclick="go('projects')">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
      Projects
      <span class="sb-badge"><?= $total ?></span>
    </button>
    <button class="sb-btn" id="nav-messages" onclick="go('messages')">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      Messages
      <?php if($msgs>0): ?><span class="sb-badge"><?= $msgs ?></span><?php endif; ?>
    </button>

    <div class="sb-sect">Site</div>
    <a href="../index.html" class="sb-btn">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
      View Site
    </a>
    <a href="logout.php" class="sb-btn red">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
      Logout
    </a>
  </nav>

  <div class="sb-foot">
    <div class="sb-user">
      <div class="sb-avatar"><?= strtoupper(substr($user,0,1)) ?></div>
      <div><div class="sb-uname"><?= htmlspecialchars($user) ?></div><div class="sb-urole">Administrator</div></div>
    </div>
  </div>
</aside>

<main class="main">
  <div class="topbar">
    <span class="tb-title" id="tb-title">Overview</span>
    <div class="tb-right">
      <button class="btn btn-dark" onclick="openAdd()">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Project
      </button>
    </div>
  </div>

  <div class="content">

    <!-- Overview -->
    <div class="pane on" id="pane-overview">
      <div class="stats">
        <div class="stat">
          <div class="stat-top">
            <div class="stat-lbl">Total Projects</div>
            <div class="stat-icon si-a"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg></div>
          </div>
          <div class="stat-num"><?= $total ?></div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="stat-lbl">Live</div>
            <div class="stat-icon si-b"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
          </div>
          <div class="stat-num"><?= $live ?></div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="stat-lbl">Messages</div>
            <div class="stat-icon si-c"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
          </div>
          <div class="stat-num"><?= $msgs ?></div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="stat-lbl">Today</div>
            <div class="stat-icon si-d"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
          </div>
          <div class="stat-num"><?= date('d') ?><span style="font-size:1rem;font-weight:400;color:#8A847C"> <?= date('M') ?></span></div>
        </div>
      </div>

      <div class="panel">
        <div class="ph"><h3>Recent Messages</h3><button class="btn btn-ghost btn-sm" onclick="go('messages')">View All</button></div>
        <?php if(empty($contacts)): ?>
        <p class="no-data">No messages yet.</p>
        <?php else: ?>
        <table>
          <thead><tr><th>Sender</th><th>Subject</th><th>Date</th></tr></thead>
          <tbody>
          <?php foreach(array_slice($contacts,0,5) as $c): ?>
          <tr>
            <td><strong><?= htmlspecialchars($c['name']) ?></strong><br><span style="color:#B6AE9F;font-size:.7rem"><?= htmlspecialchars($c['email']) ?></span></td>
            <td><?= htmlspecialchars($c['subject']) ?></td>
            <td style="font-family:'JetBrains Mono',monospace;font-size:.7rem;white-space:nowrap;color:#B6AE9F"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>

      <div class="panel">
        <div class="ph"><h3>Recent Projects</h3><button class="btn btn-ghost btn-sm" onclick="go('projects')">Manage</button></div>
        <?php if(empty($projects)): ?>
        <p class="no-data">No projects yet.</p>
        <?php else: ?>
        <table>
          <thead><tr><th>Title</th><th>Category</th><th>Status</th></tr></thead>
          <tbody>
          <?php foreach(array_slice($projects,0,5) as $p): ?>
          <tr>
            <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
            <td><span class="bdg bdg-<?= $p['category']==='fullstack'?'fs':($p['category']==='frontend'?'fe':'be') ?>"><?= $p['category'] ?></span></td>
            <td><span class="bdg bdg-<?= $p['status']==='live'?'live':'dev' ?>"><?= $p['status'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>

    <!-- Projects -->
    <div class="pane" id="pane-projects">
      <div id="pfb" class="fb"></div>
      <div class="panel">
        <div class="ph">
          <h3>All Projects <span class="ph-count"><?= $total ?> total</span></h3>
          <button class="btn btn-dark btn-sm" onclick="openAdd()">
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add New
          </button>
        </div>
        <?php if(empty($projects)): ?>
        <p class="no-data">No projects. Add your first one!</p>
        <?php else: ?>
        <table>
          <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Stack</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach($projects as $i=>$p): ?>
          <tr id="pr-<?= $p['id'] ?>">
            <td style="font-family:'JetBrains Mono',monospace;color:#C5C7BC"><?= $i+1 ?></td>
            <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
            <td><span class="bdg bdg-<?= $p['category']==='fullstack'?'fs':($p['category']==='frontend'?'fe':'be') ?>"><?= $p['category'] ?></span></td>
            <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#8A847C"><?= htmlspecialchars($p['tech_stack']) ?></td>
            <td><span class="bdg bdg-<?= $p['status']==='live'?'live':'dev' ?>"><?= $p['status'] ?></span></td>
            <td><div class="act-row">
              <button class="btn btn-outline btn-sm" onclick='edit(<?= htmlspecialchars(json_encode($p)) ?>)'>Edit</button>
              <button class="btn btn-del btn-sm" onclick="del(<?= $p['id'] ?>,'<?= htmlspecialchars(addslashes($p['title'])) ?>')">Delete</button>
            </div></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>

    <!-- Messages -->
    <div class="pane" id="pane-messages">
      <div class="panel">
        <div class="ph"><h3>All Messages <span class="ph-count"><?= $msgs ?> total</span></h3></div>
        <?php if(empty($contacts)): ?>
        <p class="no-data">No messages yet.</p>
        <?php else: ?>
        <div class="msgs">
          <?php foreach($contacts as $c): ?>
          <div class="mc">
            <div class="mc-hd">
              <div>
                <span class="mc-who"><?= htmlspecialchars($c['name']) ?></span>
                <span class="mc-mail"> — <?= htmlspecialchars($c['email']) ?></span>
              </div>
              <span class="mc-date"><?= date('d M Y, H:i', strtotime($c['created_at'])) ?></span>
            </div>
            <div class="mc-sub">📌 <?= htmlspecialchars($c['subject']) ?></div>
            <div class="mc-body"><?= nl2br(htmlspecialchars($c['message'])) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</main>

<!-- Modal -->
<div class="overlay" id="modal">
  <div class="modal">
    <div class="modal-hd">
      <h3 id="modal-title">Add Project</h3>
      <button class="mc-btn" onclick="closeModal()">✕</button>
    </div>
    <div id="mfb" class="fb"></div>
    <form id="pform">
      <input type="hidden" id="fid" name="id">
      <input type="hidden" id="faction" name="action" value="add">
      <div class="fg"><label>Title *</label><input type="text" id="ftitle" name="title" placeholder="Project name"></div>
      <div class="fg"><label>Description *</label><textarea id="fdesc" name="description" placeholder="What does this project do?"></textarea></div>
      <div class="fg"><label>Tech Stack</label><input type="text" id="ftech" name="tech_stack" placeholder="React, Node.js, MySQL…"></div>
      <div class="fg2">
        <div class="fg"><label>Category</label>
          <select id="fcat" name="category">
            <option value="fullstack">Full Stack</option>
            <option value="frontend">Frontend</option>
            <option value="backend">Backend</option>
          </select>
        </div>
        <div class="fg"><label>Status</label>
          <select id="fstatus" name="status">
            <option value="live">Live</option>
            <option value="development">Development</option>
            <option value="archived">Archived</option>
          </select>
        </div>
      </div>
      <div class="fg2">
        <div class="fg"><label>GitHub URL</label><input type="url" id="fgithub" name="github_url" placeholder="https://github.com/…"></div>
        <div class="fg"><label>Live URL</label><input type="url" id="flive" name="live_url" placeholder="https://…"></div>
      </div>
      <div class="fg"><label>Image URL</label><input type="url" id="fimg" name="image_url" placeholder="https://images.unsplash.com/…"></div>
      <div class="modal-foot">
        <button type="submit" class="btn btn-dark" style="flex:1">Save Project</button>
        <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
/* tabs */
const titles={overview:'Overview',projects:'Projects',messages:'Messages'};
function go(name){
  document.querySelectorAll('.pane').forEach(p=>p.classList.remove('on'));
  document.querySelectorAll('.sb-btn[id^=nav-]').forEach(b=>b.classList.remove('on'));
  document.getElementById('pane-'+name).classList.add('on');
  const nb=document.getElementById('nav-'+name);
  if(nb) nb.classList.add('on');
  document.getElementById('tb-title').textContent=titles[name]||name;
}

/* modal */
function openAdd(){
  document.getElementById('modal-title').textContent='Add Project';
  document.getElementById('pform').reset();
  document.getElementById('faction').value='add';
  document.getElementById('fid').value='';
  hideFb();
  document.getElementById('modal').classList.add('open');
}
function edit(p){
  document.getElementById('modal-title').textContent='Edit Project';
  document.getElementById('faction').value='edit';
  document.getElementById('fid').value=p.id;
  document.getElementById('ftitle').value=p.title||'';
  document.getElementById('fdesc').value=p.description||'';
  document.getElementById('ftech').value=p.tech_stack||'';
  document.getElementById('fcat').value=p.category||'fullstack';
  document.getElementById('fstatus').value=p.status||'live';
  document.getElementById('fgithub').value=p.github_url||'';
  document.getElementById('flive').value=p.live_url||'';
  document.getElementById('fimg').value=p.image_url||'';
  hideFb();
  document.getElementById('modal').classList.add('open');
}
function closeModal(){document.getElementById('modal').classList.remove('open')}
document.getElementById('modal').addEventListener('click',e=>{if(e.target===e.currentTarget)closeModal()});
function hideFb(){const f=document.getElementById('mfb');f.className='fb';f.textContent=''}

/* form submit */
document.getElementById('pform').addEventListener('submit',async e=>{
  e.preventDefault();
  const fb=document.getElementById('mfb');
  try{
    const res=await fetch('../php/admin_projects.php',{method:'POST',body:new FormData(e.target)});
    const d=await res.json();
    fb.className='fb show '+(d.success?'ok':'err');
    fb.textContent=d.message;
    if(d.success) setTimeout(()=>{closeModal();location.reload()},800);
  }catch{fb.className='fb show err';fb.textContent='Request failed.'}
});

/* delete */
async function del(id,title){
  if(!confirm(`Delete "${title}"?`))return;
  const fd=new FormData();fd.append('action','delete');fd.append('id',id);
  const res=await fetch('../php/admin_projects.php',{method:'POST',body:fd});
  const d=await res.json();
  const fb=document.getElementById('pfb');
  fb.className='fb show '+(d.success?'ok':'err');
  fb.textContent=d.message;
  if(d.success)document.getElementById('pr-'+id)?.remove();
}
</script>
</body>
</html>
