<?php
session_start();
require_once "config/database.php";

if (isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit; }

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];
        header("Location: dashboard.php"); exit;
    } else {
        $error = "Invalid email or password.";
    }
}
$page_title = "Login";
include "includes/header.php";
?>
<div class="auth-card card shadow-sm">
    <div class="card-body p-4">
        <h2 class="text-center mb-4">Task Tracker Login</h2>
        <?php if (isset($_GET['registered'])): ?><div class="alert alert-success">Registration successful. Please login.</div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
        <p class="text-center mt-3 mb-0">New user? <a href="register.php">Create account</a></p>
    </div>
</div>
<?php include "includes/footer.php"; ?>