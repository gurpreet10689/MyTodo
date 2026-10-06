<?php
require_once "config/database.php"; require_once "includes/auth.php";
$error="";
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $title=trim($_POST['title']??""); $description=trim($_POST['description']??"");
    $priority=$_POST['priority']??"Medium"; $status=$_POST['status']??"Pending"; $due=$_POST['due_date']??null;
    if ($title==="") $error="Task title is required.";
    elseif (!in_array($priority,['Low','Medium','High'],true) || !in_array($status,['Pending','In Progress','Completed'],true)) $error="Invalid task values.";
    else {
        $stmt=$conn->prepare("INSERT INTO tasks(user_id,title,description,priority,status,due_date) VALUES(?,?,?,?,?,NULLIF(?,''))");
        $stmt->bind_param("isssss",$_SESSION['user_id'],$title,$description,$priority,$status,$due);
        if($stmt->execute()){ header("Location: dashboard.php"); exit; } $error="Could not add task.";
    }
}
$page_title="Add Task"; include "includes/header.php";
?>
<div class="form-card card shadow-sm"><div class="card-body p-4">
<h3 class="mb-4">Add New Task</h3>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="mb-3"><label class="form-label">Task Title</label><input name="title" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="5"></textarea></div>
<div class="row">
<div class="col-md-4 mb-3"><label class="form-label">Priority</label><select name="priority" class="form-select"><option>Low</option><option selected>Medium</option><option>High</option></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option>Pending</option><option>In Progress</option><option>Completed</option></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Due Date</label><input type="date" name="due_date" class="form-control"></div>
</div>
<button class="btn btn-primary">Save Task</button> <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
</form></div></div>
<?php include "includes/footer.php"; ?>