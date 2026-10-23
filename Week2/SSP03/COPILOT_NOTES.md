# COPILOT_NOTES.md — Week 2
## SSP03 / Chapter 8 & 13

| # | File | Copilot Suggestion | Decision | Notes |
|---|------|--------------------|----------|-------|
| 1 | functions_ssp03.php | `match($day_num)` for fn_dayOfWeek | REJECTED | The assignment grades the if/elseif chain, so I wrote it with if/elseif to practice that structure. |
| 2 | functions_ssp03.php | `switch` for fn_dayDetails | ACCEPTED | It already used switch as required, with a case for each day and a default. |
| 3 | functions_ssp03.php | fn_tableRowCurrent with `background-color: yellow` | IMPROVED | Escaping was correct on all three cells, but I changed the color to `#FFFF66` to match the spec. |
| 4 | index.php | `(int) date('N')` for today's day number | IMPROVED | `date()` uses the server's timezone, so the wrong row could be highlighted. I used `new DateTime('now', new DateTimeZone('America/Chicago'))`. |
| 5 | index.php | `for` loop Mon–Sun with an inline echo in the else branch | IMPROVED | I kept the for loop but called `fn_tableRow()` in the else branch so the row HTML isn't duplicated. |
