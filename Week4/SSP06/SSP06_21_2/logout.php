<?php
declare(strict_types=1);

/*
 * logout.php - CS2623 SSP06 Exercise 21-2 (Ch21) - Author: Fraire, Fidel
 * Ends the session and returns to the login page. The remember-me cookie
 * is kept on purpose: it only pre-fills the username next time.
 */

session_start();
session_unset();

// Expire the session ID cookie in the browser as well
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 3600,
        'path'     => $params['path'],
        'domain'   => $params['domain'],
        'secure'   => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite'],
    ]);
}

session_destroy();
header('Location: login.php');
exit;
