# COPILOT_NOTES.md — Week 2
## SSP04 / Chapter 9 & 10

| # | File | Copilot Suggestion | Decision | Notes |
|---|------|--------------------|----------|-------|
| 5 | ex1_strings.php | str_contains() usage | ACCEPTED | PHP 8 — Copilot used strpos() != false (old style) |
| 6 | ex2_datetime.php | DateTime without timezone | REJECTED | Apollo runs UTC; must use DateTimeZone |
| 7 | ex2_datetime.php | diff() for age calculation | ACCEPTED | Correct DateInterval pattern |
| 8 | ex2_datetime.php | createFromFormat suggestion | IMPROVED | Added $tz parameter — Copilot omitted it |

## htmlspecialchars() vs htmlentities()
htmlspecialchars() and htmlentities() both convert characters that have special meaning in HTML—such as &, <, >, and quotes—into HTML entities. This helps prevent HTML or script injection when displaying untrusted text.

The difference is that htmlentities() also converts many other characters, including accented letters and symbols, while htmlspecialchars() converts only the HTML-special characters.


For ordinary HTML output, htmlspecialchars() is usually sufficient and commonly preferred. Specify the character encoding and use quotes handling, for example:

