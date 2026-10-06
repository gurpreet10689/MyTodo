<?php
require_once "config/database.php"; require_once "includes/auth.php";
$message=""; $error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name=trim($_POST['name']??"");
    if($name==="") $error="Name cannot be empty.";
    else {
        $stmt=$conn->prepare("UPDATE users SET name=? WHERE id=?"); $stmt->bind_param("si",$name,$_SESSION['user_id']);
        if($stmt->execute()){ $_SESSION['name']=$name; $message="Profile updated successfully."; } else $error="Update failed.";
    }
}
$page_title="Profile"; include "includes/header.php";
?>
<div class="form-card card shadow-sm"><div class="card-body p-4">
<h3>My Profile</h3><hr>
<?php if($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="mb-3"><label class="form-label">Name</label><input name="name" class="form-control" value="<?= htmlspecialchars($_SESSION['name']) ?>" required></div>
<div class="mb-3"><label class="form-label">Email</label><input class="form-control" value="<?= htmlspecialchars($_SESSION['email']) ?>" disabled></div>
<div class="mb-3"><label class="form-label">Role</label><input class="form-control" value="<?= htmlspecialchars($_SESSION['role']) ?>" disabled></div>
<button class="btn btn-primary">Update Profile</button>
</form></div></div>
<?php include "includes/footer.php"; ?>