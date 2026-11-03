
<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // TODO 7 — Authenticate the user.
    $users = loadUsers();
    $user = findUserByEmail($users, $email);

    if ($user !== null && password_verify($password, $user['password_hash'])) {
        // Create the login session.
        loginUser($user);

        // Redirect to the originally requested page if one exists.
        $redirect = $_SESSION['intended_url'] ?? 'index.php';

        // Remove the saved URL so it is only used once.
        unset($_SESSION['intended_url']);

        header('Location: ' . $redirect);
        exit;
    } else {
        // Use one generic error message for failed logins.
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container narrow">
    <section class="panel">
        <h1>Support Desk Sign In</h1>
        <p>Sign in to access the support ticket application.</p>

        <?php if ($error !== ''): ?>
            <div class="error-box"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= e($email) ?>"
                autocomplete="username"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button type="submit">Sign In</button>
        </form>
    </section>
</main>
</body>
</html>
