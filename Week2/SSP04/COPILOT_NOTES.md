# COPILOT_NOTES.md — Week 2
## SSP04 / Chapter 9 & 10

| # | File | Copilot Suggestion | Decision | Notes |
|---|------|--------------------|----------|-------|
| 6 | ex1_strings.php | `str_contains($description, 'PHP')`, echoed directly | IMPROVED | str_contains() is the right PHP 8 check, but I converted the boolean to "true"/"false" and passed it through htmlspecialchars() like every other output. |
| 7 | ex2_datetime.php | `createFromFormat('Y-m-d', '1999-05-14', $tz)` | IMPROVED | It included $tz, but echoed the date without escaping and kept the current time of day. I escaped the output and added `setTime(0, 0)` so the age math starts at midnight. |

## htmlspecialchars() vs htmlentities()
htmlspecialchars() and htmlentities() both convert characters that have special meaning in HTML—such as &, <, >, and quotes—into HTML entities. This helps prevent HTML or script injection when displaying untrusted text.

The difference is that htmlentities() also converts many other characters, including accented letters and symbols, while htmlspecialchars() converts only the HTML-special characters.


For ordinary HTML output, htmlspecialchars() is usually sufficient and commonly preferred. Specify the character encoding and use quotes handling.

