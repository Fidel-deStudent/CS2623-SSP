# CS-2623 Week 5: GitHub Copilot Awareness Log

## SSP08 Ex 03-1 (Control Structures)

| Suggestion | Action | Reason |
|---|---|---|
| `match` expression for `fn_dayOfWeek()` | Rejected | The exercise requires an `if` / `elseif` chain per the Murach Ch03 spec |
| `match` expression for `fn_gradeLabel()` | Rejected | The exercise requires a `switch` statement per the Murach Ch03 spec |
| Type hints on function parameters | Accepted | `int` type hints work with `strict_types=1` and catch the wrong type of value being passed in |
| `for` loop off-by-one (`$i <= $end + 1`) | Improved | Changed the boundary to `$i <= $end` |
| `echo` inside function body | Improved | Changed it to `return` so the page echoes the value with `htmlspecialchars()` |
| `filter_input(...) ?? 1` for the number fields | Improved | `??` only replaces `null`, but invalid input returns `false`, which becomes `0`. Added `fn_postInt()`, which checks `is_int()` |
| `if ($end < $start) $end = $start;` clamp | Rejected | It hid the start > end case, so I show an error message instead of the results |
| `declare(strict_types=1)` omitted | Improved | Added `strict_types=1` at the top so the `int` type hints are enforced |
| `while (true)` loop without a `break` | Rejected | It could run forever. Every loop has a fixed end, and the repeat word loop stops at 20 |

## SSP08 Ex 16-1 (File I/O)

| Suggestion | Action | Reason |
|---|---|---|
| `date()` without timezone in log timestamp | Improved | Replaced it with `DateTime` and `DateTimeZone('America/Chicago')` because Apollo runs on UTC |
| `fopen()` / `fgets()` for small log file | Improved | Simplified it to `file_get_contents()`, which is less code with the same result |
| User input directly in file path | Rejected | Added `basename()` to the download filename to prevent directory traversal |
| `LOCK_EX` omitted on `file_put_contents` | Improved | Added the `LOCK_EX` flag so two saves at once can't overwrite each other |
| `scandir()` result without `.` and `..` filter | Improved | Added an `is_file()` filter using `array_filter` and an arrow function |
| `chmod($logFile, 0777)` | Rejected | A world-writable file is a security risk, so I set `0644` instead |
| `FILTER_SANITIZE_SPECIAL_CHARS` on the entry | Improved | It saved `&#39;` in the file, and `htmlspecialchars()` then showed it as text. Now the entry is read as typed and escaped only on output |
| `file_get_contents()` to read the log | Accepted | It's the simplest way to read a small text file, and there is no file handle to close |
| `file_put_contents()` with `FILE_APPEND` | Accepted | It appends in one call. I added `LOCK_EX` separately |
| Read without a `file_exists()` check | Improved | Added a `file_exists()` check before reading |
| `str_ends_with()` extension check | Improved | Replaced it with `pathinfo(PATHINFO_EXTENSION)` and an allowlist, following the Ch 16 file security slides |

## SSP09 Ex 17-1 (Strings, Numbers & Dates)

| Suggestion | Action | Reason |
|---|---|---|
| `rand($min, $max)` | Improved | Replaced it with `random_int($min, $max)`, which is cryptographically secure |
| `str_contains()` suggestion | Accepted | It checks whether the text contains the search word in one clear call |
| `str_starts_with()` / `str_ends_with()` | Accepted | They're clearer than `substr()` or `strpos()` comparisons |
| `date('Y-m-d')` without timezone | Improved | Changed it to `DateTime` and `DateTimeZone('America/Chicago')` because Apollo runs on UTC |
| `strpos() !== false` for search check | Improved | Simplified it to `str_contains()`, which is easier to read and avoids the `!== false` check |
| `number_format()` on currency output | Accepted | Copilot correctly used `number_format` with 2 decimal places |
| `new DateTime('December 25')` for the Christmas countdown | Improved | After December 25 it counted back to this year's Christmas. Now it rolls over to next year |
| Fibers / async patterns | Rejected | They're out of scope for this course |
