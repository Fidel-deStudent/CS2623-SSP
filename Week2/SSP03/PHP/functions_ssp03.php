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

/**
 * Return the day name for a day number (1 = Monday ... 7 = Sunday).
 * Uses an if/elseif chain on purpose (assignment requirement, not match).
 *
 * @param int $day_num Day number 1-7.
 * @return string Day name, or "Unknown" for invalid input.
 */
function fn_dayOfWeek(int $day_num): string {
    if ($day_num === 1)     return 'Monday';
    elseif ($day_num === 2) return 'Tuesday';
    elseif ($day_num === 3) return 'Wednesday';
    elseif ($day_num === 4) return 'Thursday';
    elseif ($day_num === 5) return 'Friday';
    elseif ($day_num === 6) return 'Saturday';
    elseif ($day_num === 7) return 'Sunday';
    else                    return 'Unknown';
}

/**
 * Return the planned activity for a day number.
 * Uses a switch statement (assignment requirement).
 *
 * @param int $day_num Day number 1-7.
 * @return string Activity description, or "No schedule" for invalid input.
 */
function fn_dayDetails(int $day_num): string {
    switch ($day_num) {
        case 1:  return 'PHP lecture — Ch08 Conditionals';
        case 2:  return 'Lab: SSP03 function library';
        case 3:  return 'Study: string functions Ch09';
        case 4:  return 'Lab: SSP04 exercises';
        case 5:  return 'Quiz review + office hours';
        case 6:  return 'Project work';
        case 7:  return 'Rest day';
        default: return 'No schedule';
    }
}
