<?php
/*
 * include_dynamic_content.php
 * CS2623 SSP02 - Week 1
 * Dynamically generates a weekly planner table using a for loop.
 */
?>
<!-- Weekly Planner Table -->
<table id="weekly-planner">
    <caption>CS2623 Weekly Planner - Fidel Fraire</caption>
    <thead>
        <tr>
            <th scope="col">Day of Week</th>
            <th scope="col">Task</th>
            <th scope="col">Time (hrs.)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Loop: $counter runs 1 through 7 (one row per day of the week)
        for ($counter = 1; $counter < 8; $counter++) {
            // Study hours = half the day number, shown with 1 decimal place
            $hours = number_format($counter / 2, 1);

            // Output one table row for this day
            echo "<tr>";
            echo "    <td>" . htmlspecialchars((string)$counter, ENT_QUOTES, "UTF-8") . "</td>";
            echo "    <td>Study for CS2623</td>";
            echo "    <td>" . htmlspecialchars($hours, ENT_QUOTES, "UTF-8") . "</td>";
            echo "</tr>\n";
        }
        ?>
    </tbody>
</table>