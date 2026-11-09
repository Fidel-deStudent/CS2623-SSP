<?php
/* include_header.php - CS2623 Week 4 (SSP06 and SSP07)
   Opens the HTML document, links the Week 1 site styles, and prints the
   page header, top navigation and exercise buttons. Paths are relative to
   the exercise pages in Week4/SSP0x/SSP0x_y/.
   Set before including:
     $page_title - text for the <title>
     $hub        - 'SSP06' or 'SSP07' (picks the exercise buttons) */
$page_title = $page_title ?? 'CS2623 Week 4: Fraire';
$hub        = $hub ?? 'SSP07';

$exercise_links = $hub === 'SSP06'
    ? [
        '../SSP06_21_2/dashboard.php' => 'Exercise 21-2: Login & Authentication',
        '../SSP06_23_1/index.php'     => 'Exercise 23-1: Product Catalog & Cart',
    ]
    : [
        '../SSP07_11_1/index.php' => 'Exercise 11-1: Array Operations',
        '../SSP07_15_1/index.php' => 'Exercise 15-1: Regex & Exceptions',
    ];

// Only the SSP06 pages start a session, so this is null on the SSP07 pages
$logged_in_user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Shared site styles from SSP01, then the Week 4 styles -->
    <link rel="stylesheet" href="../../../SSP01/main.css">
    <link rel="stylesheet" href="../../CSS/week4.css">
</head>
<body>
<header>
    <h1>CS2623 <?= htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'Server-Side Programming', ENT_QUOTES, 'UTF-8') ?></h1>
    <?php if ($logged_in_user !== null): ?>
    <p class="user-bar">
        Logged in as <strong><?= htmlspecialchars((string)$logged_in_user, ENT_QUOTES, 'UTF-8') ?></strong>
        | <a href="../SSP06_21_2/logout.php">Log out</a>
    </p>
    <?php endif; ?>
</header>

<!-- Top navigation: same 12 buttons as the home page (SSP01) -->
<nav id="top-nav">
    <a href="../../../SSP01/index.php">Home</a>
    <a href="../../../SSP02/SSP02.php">SSP02</a>
    <a href="../../../Week2/SSP03/index.php">SSP03</a>
    <a href="../../../Week2/SSP04/index.php">SSP04</a>
    <a href="../../../Week3/SSP05/index.html">SSP05</a>
    <a href="../../SSP06/index.html">SSP06</a>
    <a href="../../SSP07/index.html">SSP07</a>
    <a href="../../../SSP08/SSP08.php">SSP08</a>
    <a href="../../../SSP09/SSP09.php">SSP09</a>
    <a href="../../../SSP10/SSP10.php">SSP10</a>
    <a href="../../../SSP11/SSP11.php">SSP11</a>
    <a href="../../../SSP12/SSP12.php">SSP12</a>
</nav>
<main>
    <!-- Buttons that open each exercise of this week's assignment -->
    <nav class="exercise-nav">
        <?php foreach ($exercise_links as $href => $label): ?>
        <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a>
        <?php endforeach; ?>
    </nav>
