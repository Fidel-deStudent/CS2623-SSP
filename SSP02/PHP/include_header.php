<?php /* include_header.php - CS2623 SSP02 */ ?>
<!-- Page header: server name is escaped before it is printed -->
<header>
    <h1>CS2623 <?php echo htmlspecialchars(isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : "Server-Side Programming", ENT_QUOTES, "UTF-8"); ?></h1>
</header>