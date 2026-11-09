<?php
declare(strict_types=1);

/*
 * profile.php - CS2623 SSP06 Exercise 21-2 (Ch21) - Author: Fraire, Fidel
 * Second protected page: same require 'auth.php' guard as dashboard.php.
 * Shows what the session and the remember-me cookie hold for this user.
 */

require 'auth.php';      // redirects if not logged in

$tz       = new DateTimeZone('America/Chicago');
$login_at = new DateTime('@' . (int)$_SESSION['login_at']);
$login_at->setTimezone($tz);

$minutes_in = intdiv(time() - (int)$_SESSION['login_at'], 60);

$remembered = filter_input(INPUT_COOKIE, 'ssp06_remember_user') ?? '';
$cart_items = array_sum($_SESSION['cart'] ?? []);

$page_title = 'CS2623 SSP06 Ex 21-2 Profile: Fraire';
$hub        = 'SSP06';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>Profile — <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?></h2>

    <nav class="sub-nav">
        <a href="dashboard.php">Dashboard</a> |
        <a href="profile.php">Profile</a> |
        <a href="../SSP06_23_1/index.php">Guitar Shop</a> |
        <a href="logout.php">Log out</a>
    </nav>

    <table>
        <tr>
            <th>Item</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Username</td>
            <td><?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <td>Logged in at</td>
            <td><?= htmlspecialchars($login_at->format('F j, Y, g:i A T'), ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <td>Time logged in</td>
            <td><?= $minutes_in ?> minute<?= $minutes_in === 1 ? '' : 's' ?></td>
        </tr>
        <tr>
            <td>Remember-me cookie</td>
            <td><?= $remembered !== ''
                    ? 'Set — username "' . htmlspecialchars($remembered, ENT_QUOTES, 'UTF-8') . '" (30 days)'
                    : 'Not set' ?></td>
        </tr>
        <tr>
            <td>Items in cart</td>
            <td><?= (int)$cart_items ?></td>
        </tr>
    </table>
<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
