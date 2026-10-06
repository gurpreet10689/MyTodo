<?php
require_once "config/database.php";
require_once "includes/auth.php";

$user_id = $_SESSION['user_id'];
$search = trim($_GET['search'] ?? "");
$status = $_GET['status'] ?? "";
$priority = $_GET['priority'] ?? "";

$counts = ['total'=>0,'pending'=>0,'in_progress'=>0,'completed'=>0];
$stmt = $conn->prepare("SELECT COUNT(*) total,
SUM(status='Pending') pending,
SUM(status='In Progress') in_progress,
SUM(status='Completed') completed
FROM tasks WHERE user_id=?");
$stmt->bind_param("i", $user_id); $stmt->execute();
$counts = $stmt->get_result()->fetch_assoc(); $stmt->close();

$sql = "SELECT * FROM tasks WHERE user_id=?";
$params = [$user_id]; $types = "i";
if ($search !== "") { $sql .= " AND (title LIKE ? OR description LIKE ?)"; $like="%$search%"; $params[]=$like; $params[]=$like; $types.="ss"; }
if (in_array($status, ['Pending','In Progress','Completed'], true)) { $sql .= " AND status=?"; $params[]=$status; $types.="s"; }
if (in_array($priority, ['Low','Medium','High'], true)) { $sql .= " AND priority=?"; $params[]=$priority; $types.="s"; }
$sql .= " ORDER BY CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC, created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute(); $tasks = $stmt->get_result();
$page_title = "Dashboard";
include "includes/header.php";
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="mb-1">Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h2><p class="text-muted mb-0">Manage your tasks efficiently.</p></div>
    <a href="add_task.php" class="btn btn-primary">+ Add Task</a>
</div>

<div class="row g-3 mb-4">
<?php foreach ([['total','Total Tasks','primary'],['pending','Pending','warning'],['in_progress','In Progress','info'],['completed','Completed','success']] as $c): ?>
<div class="col-md-3"><div class="stat-card card border-0 shadow-sm"><div class="card-body"><div class="text-muted"><?= $c[1] ?></div><div class="display-6 fw-bold text-<?= $c[2] ?>"><?= (int)$counts[$c[0]] ?></div></div></div></div>
<?php endforeach; ?>
</div>

<div class="card shadow-sm mb-4">
<div class="card-body">
<form class="row g-2">
<div class="col-md-5"><input name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search tasks..."></div>
<div class="col-md-3"><select name="status" class="form-select"><option value="">All Statuses</option><?php foreach(['Pending','In Progress','Completed'] as $x): ?><option <?= $status===$x?'selected':'' ?>><?= $x ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><select name="priority" class="form-select"><option value="">All Priorities</option><?php foreach(['Low','Medium','High'] as $x): ?><option <?= $priority===$x?'selected':'' ?>><?= $x ?></option><?php endforeach; ?></select></div>
<div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Filter</button></div>
</form>
</div></div>

<div class="card shadow-sm">
<div class="card-body">
<h5 class="mb-3">My Tasks</h5>
<div class="table-responsive">
<table class="table table-hover align-middle">
<thead><tr><th>Task</th><th>Priority</th><th>Due Date</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
<tbody>
<?php if ($tasks->num_rows === 0): ?>
<tr><td colspan="5" class="text-center py-4 text-muted">No tasks found.</td></tr>
<?php else: while($task=$tasks->fetch_assoc()): ?>
<tr>
<td><strong><?= htmlspecialchars($task['title']) ?></strong><br><small class="text-muted"><?= htmlspecialchars(mb_strimwidth($task['description'] ?? '',0,70,'...')) ?></small></td>
<td><span class="badge bg-<?= $task['priority']==='High'?'danger':($task['priority']==='Medium'?'warning text-dark':'secondary') ?>"><?= $task['priority'] ?></span></td>
<td><?= $task['due_date'] ? date('d M Y',strtotime($task['due_date'])) : '—' ?></td>
<td><span class="badge bg-<?= $task['status']==='Completed'?'success':($task['status']==='In Progress'?'info':'warning text-dark') ?>"><?= $task['status'] ?></span></td>
<td class="text-end">
<a href="view_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a>
<a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
<a href="delete_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-danger delete-btn">Delete</a>
</td></tr>
<?php endwhile; endif; ?>
</tbody></table>
</div></div></div>
<?php include "includes/footer.php"; ?>