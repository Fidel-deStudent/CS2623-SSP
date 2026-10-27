<!DOCTYPE html>
<!-- CS2623 SSP01 - index.php - Author: Fraire, Fidel -->
<html lang="en">
<head>
    <!-- Character set and responsive viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page information -->
    <meta name="author" content="Fraire, Fidel">
    <meta name="description" content="CS2623 Server-Side Programming - SSP01">
    <meta name="keywords" content="PHP, server-side, CS2623, OCCC">
    <title>CS2623 SSP01: Fraire</title>

    <!-- External stylesheet (no inline styles allowed) -->
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <!-- Page header -->
    <header>
        <h1>CS2623 Server-Side Programming</h1>
    </header>

    <!-- Top navigation: links to all 12 SSP pages -->
    <nav id="top-nav">
        <a href="../SSP01/index.php">Home</a>
        <a href="../SSP02/SSP02.php">SSP02</a>
        <a href="../Week2/SSP03/index.php">SSP03</a>
        <a href="../Week2/SSP04/index.php">SSP04</a>
        <a href="../Week3/SSP05/index.html">SSP05</a>
        <a href="../SSP06/SSP06.php">SSP06</a>
        <a href="../SSP07/SSP07.php">SSP07</a>
        <a href="../SSP08/SSP08.php">SSP08</a>
        <a href="../SSP09/SSP09.php">SSP09</a>
        <a href="../SSP10/SSP10.php">SSP10</a>
        <a href="../SSP11/SSP11.php">SSP11</a>
        <a href="../SSP12/SSP12.php">SSP12</a>
    </nav>

    <!-- Main content -->
    <main>
        <!-- Plain HTML test line -->
        <p>Hello World (HTML)</p>

        <!-- PHP echo test: htmlspecialchars() is required on all echoed output -->
        <?php echo htmlspecialchars('<p>Hello World (PHP Testing)</p>'); ?>
    </main>

    <!-- Page footer -->
    <footer>
        <p>Disclaimer: Oklahoma City Community College does not necessarily
        endorse the content or the respective links of this web page.</p>
    </footer>
</body>
</html>