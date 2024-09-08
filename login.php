<?php
session_start();
//if user is logged in, redirect to the next page
if (!empty($_SESSION['user'])) {
    header('Location: ' . ($_POST['next'] ?? 'edit.php'));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 300px; text-align: center; }
        .input-field, .login-btn { margin: 10px 0; padding: 10px; border-radius: 5px; width: calc(100% - 22px); }
        .login-btn { background-color: #0052cc; color: white; border: none; cursor: pointer; }
        .login-btn:hover { background-color: #003d99; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="login-container">

    <h2>Host Login</h2>
    <form action="handleLogin.php" method="POST">
        <input type="text" name="username" class="input-field" placeholder="Username" required>
        <input type="password" name="password" class="input-field" placeholder="Password" required>
        <input type="hidden" name="next" value="<?php echo htmlspecialchars($_GET['next'] ?? 'edit.php'); ?>">
        <button type="submit" class="login-btn">Login</button><br>
        <a href="index.php">🏠Back to home</a>
    </form>
    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
</div>

</body>
</html>