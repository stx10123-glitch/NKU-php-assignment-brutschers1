
<?php
require_once __DIR__ . '/includes/auth.php';

// TODO 8 — Complete logout.

// Only allow logout requests using POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// End the user's session.
logoutUser();

// Redirect to the login page.
header('Location: login.php');
exit;
