<?php
declare(strict_types=1);

/*
 * ex2_datetime.php - CS2623 SSP04 Exercise 2 (Ch10)
 * Current date/time, age since a birthdate, and a countdown to semester end.
 * Apollo runs UTC, so every DateTime uses the America/Chicago timezone.
 */

// Current time
$tz  = new DateTimeZone('America/Chicago');
$now = new DateTime('now', $tz);

$today_name = $now->format('l');      // e.g. "Tuesday"
$today_date = $now->format('F j, Y'); // e.g. "October 10, 2026"
$today_time = $now->format('g:i:s A'); // e.g. "2:35:04 PM"
$tz_abbr    = $now->format('T');      // "CDT" or "CST" depending on the season

// Countdown to semester end (adjust date as needed)
$end_date  = new DateTime('2026-12-15', $tz);
$interval  = $now->diff($end_date);
$days_left = $interval->days;
$is_past   = $interval->invert === 1;

// Parse a date string with createFromFormat()
$birth_str  = '1999-05-14'; // Change to your own birthdate
$birth_date = DateTime::createFromFormat('Y-m-d', $birth_str, $tz);
if ($birth_date === false) {
    exit('Invalid birthdate: ' . htmlspecialchars($birth_str, ENT_QUOTES, 'UTF-8'));
}
$birth_date->setTime(0, 0); // createFromFormat() keeps the current time unless reset
$age_interval = $birth_date->diff($now);

// Shared header and footer from SSP03 (same look as the Week 1 pages)
$page_title = 'CS2623 SSP04 Ex 2: Fraire';
require_once __DIR__ . '/../SSP03/PHP/include_header.php';
?>
    <?php require_once __DIR__ . '/PHP/include_exerciseNav.php'; ?>
    <h2><?php echo htmlspecialchars('SSP04 Exercise 2 — PHP Date & Time', ENT_QUOTES, 'UTF-8'); ?></h2>

    <h3><?php echo htmlspecialchars('Current Date and Time', ENT_QUOTES, 'UTF-8'); ?></h3>
    <?php
    echo '<p>Today is: ' . htmlspecialchars($today_name . ', ' . $today_date, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>Day of the week: ' . htmlspecialchars($today_name, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>Current time (' . htmlspecialchars($tz_abbr, ENT_QUOTES, 'UTF-8') . '): '
        . htmlspecialchars($today_time, ENT_QUOTES, 'UTF-8') . '</p>';
    ?>

    <h3><?php echo htmlspecialchars('Countdown to Semester End', ENT_QUOTES, 'UTF-8'); ?></h3>
    <?php
    echo '<p>Semester ends: ' . htmlspecialchars($end_date->format('l, F j, Y'), ENT_QUOTES, 'UTF-8') . '</p>';
    if ($is_past) {
        echo '<p>' . htmlspecialchars('The semester has already ended.', ENT_QUOTES, 'UTF-8') . '</p>';
    } else {
        echo '<p>Days until semester end: ';
        echo htmlspecialchars((string)$days_left, ENT_QUOTES, 'UTF-8');
        echo ' days</p>';
        echo '<p>Breakdown: '
            . htmlspecialchars($interval->format('%m months, %d days, %h hours, %i minutes'), ENT_QUOTES, 'UTF-8')
            . '</p>';
    }
    ?>

    <h3><?php echo htmlspecialchars('Time Since Birthdate', ENT_QUOTES, 'UTF-8'); ?></h3>
    <?php
    echo '<p>Birthdate (parsed with createFromFormat): '
        . htmlspecialchars($birth_date->format('F j, Y'), ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>Age in years: ' . htmlspecialchars((string)$age_interval->y, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>Age in days: ' . htmlspecialchars((string)$age_interval->days, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>Exact age: '
        . htmlspecialchars($age_interval->format('%y years, %m months, %d days'), ENT_QUOTES, 'UTF-8')
        . '</p>';
    ?>
<?php require_once __DIR__ . '/../SSP03/PHP/include_footer.php'; ?>
