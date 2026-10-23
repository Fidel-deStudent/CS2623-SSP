<?php
declare(strict_types=1);

/*
 * functions_ssp03.php - CS2623 SSP03 (Week 2)
 * Function library for the weekly study planner.
 * Every value echoed to the browser goes through htmlspecialchars().
 */

/**
 * Open the planner table and print the caption row and the column headings.
 *
 * @param string $caption Text shown in the top header row.
 * @return void
 */
function fn_tableOpen(string $caption): void {
    echo '<table border="1" cellpadding="6" cellspacing="0">';
    echo '<tr style="background-color:#1B2A6B;color:#FFFFFF;">';
    echo '  <th colspan="3">';
    echo htmlspecialchars($caption, ENT_QUOTES, 'UTF-8');
    echo '  </th>';
    echo '</tr>';
    echo '<tr style="background-color:#006B7D;color:#FFFFFF;">';
    echo '<th>Day</th><th>Details</th><th>Time</th>';
    echo '</tr>';
}

/**
 * Close the planner table.
 *
 * @return void
 */
function fn_tableClose(): void {
    echo '</table>';
}

/**
 * Print a highlighted (yellow) row for today's entry.
 *
 * @param string $day     Day name.
 * @param string $details Activity for the day.
 * @param string $time    Time of the activity.
 * @return void
 */
function fn_tableRowCurrent(string $day, string $details, string $time): void {
    echo '<tr style="background-color:#FFFF66;">';
    echo '<td>' . htmlspecialchars($day, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($details, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($time, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '</tr>';
}

/**
 * Print a normal (not highlighted) row.
 *
 * @param string $day     Day name.
 * @param string $details Activity for the day.
 * @param string $time    Time of the activity.
 * @return void
 */
function fn_tableRow(string $day, string $details, string $time): void {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($day, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($details, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($time, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '</tr>';
}
