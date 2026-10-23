<?php
/* include_header.php - CS2623 SSP03
   Opens the HTML document, links the Week 1 site styles, and prints the
   page header and top navigation. Set $page_title before including. */
$page_title = isset($page_title) ? $page_title : 'CS2623 SSP03: Fraire';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>

    <!-- Shared site styles from SSP01, then the Week 2 styles -->
    <link rel="stylesheet" href="../../SSP01/main.css">
    <link rel="stylesheet" href="../SSP03/CSS/week2.css">
</head>
<body>
<header>
    <h1>CS2623 <?php echo htmlspecialchars(isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'Server-Side Programming', ENT_QUOTES, 'UTF-8'); ?></h1>
</header>

<!-- Top navigation: same 12 buttons as the home page (SSP01) -->
<nav id="top-nav">
    <a href="../../SSP01/index.php">Home</a>
    <a href="../../SSP02/SSP02.php">SSP02</a>
    <a href="../SSP03/index.php">SSP03</a>
    <a href="../SSP04/index.php">SSP04</a>
    <a href="../../SSP05/SSP05.php">SSP05</a>
    <a href="../../SSP06/SSP06.php">SSP06</a>
    <a href="../../SSP07/SSP07.php">SSP07</a>
    <a href="../../SSP08/SSP08.php">SSP08</a>
    <a href="../../SSP09/SSP09.php">SSP09</a>
    <a href="../../SSP10/SSP10.php">SSP10</a>
    <a href="../../SSP11/SSP11.php">SSP11</a>
    <a href="../../SSP12/SSP12.php">SSP12</a>
</nav>
<main>
