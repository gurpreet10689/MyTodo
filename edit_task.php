<?php
require_once "config/database.php"; require_once "includes/auth.php";
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("SELECT * FROM tasks WHERE id=? AND user_id=?"); $stmt->bind_param("ii",$id,$_SESSION['user_id']); $stmt->execute(); $task=$stmt->get_result()->fetch_assoc(); $stmt->close();
if(!$task){ header("Location: dashboard.php"); exit; }
$error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $title=trim($_POST['title']??""); $description=trim($_POST['description']??""); $priority=$_POST['priority']; $status=$_POST['status']; $due=$_POST['due_date']??"";
    if($title==="") $error="Task title is required.";
    else {
        $stmt=$conn->prepare("UPDATE tasks SET title=?,description=?,priority=?,status=?,due_date=NULLIF(?,''),updated_at=NOW() WHERE id=? AND user_id=?");
        $stmt->bind_param("sssssii",$title,$description,$priority,$status,$due,$id,$_SESSION['user_id']);
        if($stmt->execute()){ header("Location: dashboard.php"); exit; } $error="Could not update task.";
    }
}
$page_title="Edit Task"; include "includes/header.php";
?>
<div class="form-card card shadow-sm"><div class="card-body p-4">
<h3 class="mb-4">Edit Task</h3>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="mb-3"><label class="form-label">Task Title</label><input name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($task['description']) ?></textarea></div>
<div class="row">
<div class="col-md-4 mb-3"><label class="form-label">Priority</label><select name="priority" class="form-select"><?php foreach(['Low','Medium','High'] as $x): ?><option <?= $task['priority']===$x?'selected':'' ?>><?= $x ?></option><?php endforeach; ?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach(['Pending','In Progress','Completed'] as $x): ?><option <?= $task['status']===$x?'selected':'' ?>><?= $x ?></option><?php endforeach; ?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Due Date</label><input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($task['due_date'] ?? '') ?>"></div>
</div>
<button class="btn btn-primary">Update Task</button> <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
</form></div></div>
<?php include "includes/footer.php"; ?>