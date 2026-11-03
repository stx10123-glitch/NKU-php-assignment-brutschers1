
<?php

/*
 * This file is included by pages that need session/authentication behavior.
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/*
 * Return the currently authenticated user.
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool
{
    return currentUser() !== null;
}

/*
 * TODO 1 — Login session creation.
 */
function loginUser(array $user): void
{
    session_regenerate_id(true);

    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
}

/*
 * TODO 2 — Protect private pages.
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? 'index.php';

        header('Location: login.php');
        exit;
    }
}

/*
 * TODO 3 — Check user permissions.
 */
function requireRole(string $requiredRole): void
{
    requireLogin();

    if (currentUser()['role'] !== $requiredRole) {
        http_response_code(403);
        exit('Access Denied: You do not have permission to view this page.');
    }
}

/*
 * TODO 4 — Store a one-time message.
 */
function setFlash(string $message): void
{
    $_SESSION['flash'] = $message;
}

/*
 * TODO 5 — Retrieve and remove a one-time message.
 */
function getFlash(): ?string
{
    $message = $_SESSION['flash'] ?? null;

    unset($_SESSION['flash']);

    return $message;
}

/*
 * TODO 6 — End the authenticated session.
 */
function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
