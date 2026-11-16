<?php
/* include_footer.php - CS2623 Week 5
   Prints the last-modified date of the requested page and closes the document.
   Apollo runs UTC, so the date is converted to America/Chicago. */

// Only call filemtime() when the script file really exists
$script_file = $_SERVER['SCRIPT_FILENAME'] ?? '';
if ($script_file !== '' && is_file($script_file)) {
    $modified = new DateTime('@' . filemtime($script_file));
    $modified->setTimezone(new DateTimeZone('America/Chicago'));
    $last_modified = $modified->format('F j, Y, g:i a');
} else {
    // Show "Unknown" instead of the current time, which would be misleading
    $last_modified = 'Unknown';
}
?>
</main>
<footer>
    <p>Last modified: <?= htmlspecialchars($last_modified, ENT_QUOTES, 'UTF-8') ?></p>
    <p>Disclaimer: Oklahoma City Community College does not necessarily
        endorse the content or the respective links of this web page.</p>
</footer>
</body>
</html>
