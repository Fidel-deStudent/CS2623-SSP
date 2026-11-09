<?php
declare(strict_types=1);

/*
 * dashboard.php - CS2623 SSP06 Exercise 21-2 (Ch21) - Author: Fraire, Fidel
 * Protected page: auth.php redirects to login.php when nobody is logged in.
 * The shared header shows the logged-in user's name.
 */

require 'auth.php';      // redirects if not logged in

// Apollo runs UTC, so show the login time in US Central time
$login_at = new DateTime('@' . (int)$_SESSION['login_at']);
$login_at->setTimezone(new DateTimeZone('America/Chicago'));

$page_title = 'CS2623 SSP06 Ex 21-2 Dashboard: Fraire';
$hub        = 'SSP06';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?>!</h2>

    <nav class="sub-nav">
        <a href="dashboard.php">Dashboard</a> |
        <a href="profile.php">Profile</a> |
        <a href="../SSP06_23_1/index.php">Guitar Shop</a> |
        <a href="logout.php">Log out</a>
    </nav>

    <p class="info">
        Logged in at: <?= htmlspecialchars($login_at->format('g:i A T'), ENT_QUOTES, 'UTF-8') ?><br>
        This page and the profile page both start with <code>require 'auth.php'</code>,
        so they redirect to the login page when no user is in the session.
    </p>
<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
