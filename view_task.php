<?php
require_once "config/database.php"; require_once "includes/auth.php";
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("SELECT * FROM tasks WHERE id=? AND user_id=?"); $stmt->bind_param("ii",$id,$_SESSION['user_id']); $stmt->execute(); $task=$stmt->get_result()->fetch_assoc(); $stmt->close();
if(!$task){ header("Location: dashboard.php"); exit; }
$page_title="View Task"; include "includes/header.php";
?>
<div class="card shadow-sm"><div class="card-body p-4">
<div class="d-flex justify-content-between"><h3><?= htmlspecialchars($task['title']) ?></h3><span class="badge bg-<?= $task['status']==='Completed'?'success':($task['status']==='In Progress'?'info':'warning text-dark') ?> fs-6"><?= $task['status'] ?></span></div>
<hr>
<p><?= nl2br(htmlspecialchars($task['description'] ?: 'No description provided.')) ?></p>
<div class="row mt-4">
<div class="col-md-4"><strong>Priority</strong><p><?= htmlspecialchars($task['priority']) ?></p></div>
<div class="col-md-4"><strong>Due Date</strong><p><?= $task['due_date'] ? date('d M Y',strtotime($task['due_date'])) : 'No due date' ?></p></div>
<div class="col-md-4"><strong>Created</strong><p><?= date('d M Y H:i',strtotime($task['created_at'])) ?></p></div>
</div>
<a href="edit_task.php?id=<?= $id ?>" class="btn btn-primary">Edit</a>
<a href="dashboard.php" class="btn btn-secondary">Back</a>
</div></div>
<?php include "includes/footer.php"; ?>