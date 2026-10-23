<?php
declare(strict_types=1);

/*
 * index.php - CS2623 SSP04 landing page
 * Links to the two SSP04 exercises, each on its own page.
 */

// Shared header and footer from SSP03 (same look as the Week 1 pages)
$page_title = 'CS2623 SSP04: Fraire';
require_once __DIR__ . '/../SSP03/PHP/include_header.php';
?>
    <h2><?php echo htmlspecialchars('SSP04 — String Functions & Date/Time', ENT_QUOTES, 'UTF-8'); ?></h2>

    <p><?php echo htmlspecialchars('Choose an exercise:', ENT_QUOTES, 'UTF-8'); ?></p>
    <ul>
        <li><?php echo htmlspecialchars('Exercise 1 (Ch09): PHP string functions on a sample product description.', ENT_QUOTES, 'UTF-8'); ?></li>
        <li><?php echo htmlspecialchars('Exercise 2 (Ch10): current date and time, a semester countdown, and age from a birthdate.', ENT_QUOTES, 'UTF-8'); ?></li>
    </ul>

    <?php require_once __DIR__ . '/PHP/include_exerciseNav.php'; ?>
<?php require_once __DIR__ . '/../SSP03/PHP/include_footer.php'; ?>
