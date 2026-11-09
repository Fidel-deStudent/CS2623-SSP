<?php
declare(strict_types=1);

/*
 * auth.php - CS2623 SSP06 Exercise 21-2 (Ch21) - Author: Fraire, Fidel
 * Include at the top of every protected page (require 'auth.php').
 * Sends visitors who are not logged in to the login page.
 */

// Start the session only if the including page hasn't already
// (a second session_start() raises a notice in PHP 8)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    // Path works from both SSP06_21_2/ and SSP06_23_1/ (sibling folders),
    // so the cart page can reuse this file
    header('Location: ../SSP06_21_2/login.php');
    exit;
}
