<?php
/* include_header.php - CS2623 Week 5 (SSP08 and SSP09)
   Opens the HTML document, links the Week 1 site styles, and prints the
   page header, top navigation and exercise buttons. Paths are relative to
   the exercise pages in Week5/SSP0x/SSP0x_y/.
   Set before including:
     $page_title - text for the <title>
     $hub        - 'SSP08' or 'SSP09' (picks the exercise buttons) */
$page_title = $page_title ?? 'CS2623 Week 5: Fraire';
$hub        = $hub ?? 'SSP08';

$exercise_links = $hub === 'SSP08'
    ? [
        '../SSP08_03_1/index.php' => 'Exercise 03-1: Control Structures',
        '../SSP08_16_1/index.php' => 'Exercise 16-1: File I/O',
    ]
    : [
        '../SSP09_17_1/index.php' => 'Exercise 17-1: Strings, Numbers & Dates',
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Shared site styles from SSP01, then the Week 5 styles -->
    <link rel="stylesheet" href="../../../SSP01/main.css">
    <link rel="stylesheet" href="../../CSS/week5.css">
</head>
<body>
<header>
    <h1>CS2623 <?= htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'Server-Side Programming', ENT_QUOTES, 'UTF-8') ?></h1>
</header>

<!-- Top navigation: same 12 buttons as the home page (SSP01) -->
<nav id="top-nav">
    <a href="../../../SSP01/index.php">Home</a>
    <a href="../../../SSP02/SSP02.php">SSP02</a>
    <a href="../../../Week2/SSP03/index.php">SSP03</a>
    <a href="../../../Week2/SSP04/index.php">SSP04</a>
    <a href="../../../Week3/SSP05/index.html">SSP05</a>
    <a href="../../../Week4/SSP06/index.html">SSP06</a>
    <a href="../../../Week4/SSP07/index.html">SSP07</a>
    <a href="../../SSP08/index.html">SSP08</a>
    <a href="../../SSP09/index.html">SSP09</a>
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
