<?php
declare(strict_types=1);

/*
 * login.php - CS2623 SSP06 Exercise 21-2 (Ch21) - Author: Fraire, Fidel
 * Self-processing login form. Checks the username/password against a PHP
 * array of password_hash() hashes, regenerates the session ID on success,
 * locks the form after 3 failed attempts, and can remember the username
 * (never the password) in a 30-day cookie.
 */

session_start();

// Simulated user store — in production this is a DB query.
// Only hashes are stored, never plain-text passwords.
$users = [
    'admin'  => password_hash('secret123', PASSWORD_DEFAULT),
    'jsmith' => password_hash('php4life!', PASSWORD_DEFAULT),
];

const MAX_ATTEMPTS    = 3;
const LOCK_SECONDS    = 300;                    // 5-minute lockout
const REMEMBER_COOKIE = 'ssp06_remember_user';
const REMEMBER_DAYS   = 30;

// Already logged in: nothing to do here
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$_SESSION['attempts'] ??= 0;

// When the lockout time has passed, unlock and reset the counter
if (isset($_SESSION['locked_until']) && time() >= $_SESSION['locked_until']) {
    unset($_SESSION['locked_until']);
    $_SESSION['attempts'] = 0;
}

/** Cookie options shared by setting and deleting the remember-me cookie. */
function rememberCookieOptions(int $expires): array
{
    return [
        'expires'  => $expires,
        'secure'   => ($_SERVER['HTTPS'] ?? 'off') !== 'off',
        'httponly' => true,     // JavaScript can't read it
        'samesite' => 'Lax',
    ];
}

$error = '';

// Pre-fill the username from the remember-me cookie (if any)
$remembered    = filter_input(INPUT_COOKIE, REMEMBER_COOKIE) ?? '';
$username      = $remembered;
$remember_me   = $remembered !== '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username    = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $password    = filter_input(INPUT_POST, 'password', FILTER_DEFAULT) ?? '';
    $remember_me = filter_input(INPUT_POST, 'remember_me') === '1';

    if (isset($_SESSION['locked_until'])) {
        // Locked: don't even check the password (the message is set below)
    } elseif (isset($users[$username]) && password_verify($password, $users[$username])) {
        session_regenerate_id(true);            // ← prevents session fixation
        $_SESSION['user']     = $username;
        $_SESSION['login_at'] = time();
        unset($_SESSION['attempts'], $_SESSION['locked_until']);

        // Remember me: store the USERNAME only, for 30 days.
        // Unchecked: delete any earlier cookie.
        if ($remember_me) {
            setcookie(REMEMBER_COOKIE, $username, rememberCookieOptions(time() + REMEMBER_DAYS * 24 * 60 * 60));
        } else {
            setcookie(REMEMBER_COOKIE, '', rememberCookieOptions(time() - 3600));
        }

        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['attempts']++;
        if ($_SESSION['attempts'] >= MAX_ATTEMPTS) {
            $_SESSION['locked_until'] = time() + LOCK_SECONDS;
        } else {
            $left  = MAX_ATTEMPTS - $_SESSION['attempts'];
            $error = "Invalid username or password. $left attempt" . ($left === 1 ? '' : 's') . ' left.';
        }
    }
}

// Locked message (also shown on a plain GET while the lock is active).
// Note: the counter lives in the session, so clearing cookies resets it;
// a real site would store failed attempts per account in the database.
$locked = isset($_SESSION['locked_until']);
if ($locked) {
    $minutes = (int)ceil(($_SESSION['locked_until'] - time()) / 60);
    $error   = 'Account locked after ' . MAX_ATTEMPTS . ' failed attempts. Try again in '
             . $minutes . ' minute' . ($minutes === 1 ? '' : 's') . '.';
}

$page_title = 'CS2623 SSP06 Ex 21-2 Login: Fraire';
$hub        = 'SSP06';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>SSP06 Exercise 21-2 — Login</h2>

    <?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <fieldset <?= $locked ? 'disabled' : '' ?>>
            <legend>Log in</legend>
            <div class="form-row">
                <label for="username">Username:</label>
                <!-- double_encode=false: the value is already encoded by FILTER_SANITIZE_SPECIAL_CHARS -->
                <input type="text" id="username" name="username" autocomplete="username" required
                       value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8', false) ?>">
            </div>
            <div class="form-row">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <div class="form-row">
                <span class="field-name"></span>
                <label>
                    <input type="checkbox" name="remember_me" value="1" <?= $remember_me ? 'checked' : '' ?>>
                    Remember me (username only, 30 days)
                </label>
            </div>
            <button type="submit">Log in</button>
        </fieldset>
    </form>

    <div class="info">
        <strong>Demo accounts</strong>
        <table class="demo-accounts">
            <tr><th>Username</th><th>Password</th></tr>
            <tr><td><code>admin</code></td><td><code>secret123</code></td></tr>
            <tr><td><code>jsmith</code></td><td><code>php4life!</code></td></tr>
        </table>
        After <?= MAX_ATTEMPTS ?> wrong passwords the form locks for <?= LOCK_SECONDS / 60 ?> minutes.
    </div>
<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
