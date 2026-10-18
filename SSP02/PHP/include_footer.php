<?php /* include_footer.php - CS2623 SSP02
   Get the last-modified time of the page being requested.
   filemtime() on SCRIPT_FILENAME is more accurate than getlastmod()
   when files are pulled in through includes. */
$last_modified = date("F j, Y, g:i a", filemtime($_SERVER["SCRIPT_FILENAME"]));
?>
<!-- Page footer -->
<footer>
    <p>Last modified: <?php echo htmlspecialchars($last_modified, ENT_QUOTES, "UTF-8"); ?></p>
    <p>Disclaimer: Oklahoma City Community College does not necessarily
    endorse the content or the respective links of this web page.</p>
</footer>