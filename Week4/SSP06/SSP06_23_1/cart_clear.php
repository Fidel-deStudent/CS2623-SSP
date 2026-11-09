<?php
declare(strict_types=1);

/*
 * cart_clear.php - CS2623 SSP06 Exercise 23-1 (Ch23) - Author: Fraire, Fidel
 * Empties the shopping cart and goes back to the catalog.
 */

session_start();
require '../SSP06_21_2/auth.php';    // only logged-in users have a cart

unset($_SESSION['cart']);

header('Location: index.php');
exit;
