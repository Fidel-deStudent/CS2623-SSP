<?php /* include_footer.php - CS2623 SSP02
   Get the last-modified time of the page being requested. */

// Use one timezone so the date is the same on every server
date_default_timezone_set("America/Chicago");

// Only call filemtime() when the script file really exists
$script_file = $_SERVER["SCRIPT_FILENAME"] ?? "";
if ($script_file !== "" && is_file($script_file)) {
    $last_modified = date("F j, Y, g:i a", filemtime($script_file));
} else {
    // Show "Unknown" instead of the current time, which would be misleading
    $last_modified = "Unknown";
}
?>
<footer>
    <p>Last modified: <?php echo htmlspecialchars($last_modified, ENT_QUOTES, "UTF-8"); ?></p>
    <p>Disclaimer: Oklahoma City Community College does not necessarily
        endorse the content or the respective links of this web page.</p>
</footer>