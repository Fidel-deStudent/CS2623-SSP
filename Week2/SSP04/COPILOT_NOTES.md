# COPILOT_NOTES.md — Week 2
## SSP04 / Chapter 9 & 10

| # | File | Copilot Suggestion | Decision | Notes |
|---|------|--------------------|----------|-------|
| 5 | ex1_strings.php | str_contains() usage | ACCEPTED | PHP 8 — Copilot used strpos() != false (old style) |
| 6 | ex2_datetime.php | DateTime without timezone | REJECTED | Apollo runs UTC; must use DateTimeZone |
| 7 | ex2_datetime.php | diff() for age calculation | ACCEPTED | Correct DateInterval pattern |
| 8 | ex2_datetime.php | createFromFormat suggestion | IMPROVED | Added $tz parameter — Copilot omitted it |

## htmlspecialchars() vs htmlentities()
- **htmlspecialchars()**: Encodes only special HTML syntax characters (`<`, `>`, `&`, `"`, `'`). Recommended for modern UTF-8 pages and course XSS standards.
- **htmlentities()**: Encodes all characters with HTML entity equivalents (like `&copy;`). Older approach, unnecessary when using UTF-8.
