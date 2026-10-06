<?php
require_once "config/database.php"; require_once "includes/auth.php";
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
$stmt->bind_param("ii",$id,$_SESSION['user_id']); $stmt->execute(); $stmt->close();
header("Location: dashboard.php"); exit;
?>