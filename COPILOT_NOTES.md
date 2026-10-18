# COPILOT_NOTES.md - CS 2623 Week 1

## Accepted
- Copilot's security review suggested setting the timezone with `date_default_timezone_set('America/Chicago')`. I accepted it so the "Last modified" date is consistent on localhost and on Apollo.
- Copilot confirmed that `htmlspecialchars($last_modified, ENT_QUOTES, "UTF-8")` is the right way to escape the footer output, so I kept it unchanged.

## Rejected
- Copilot suggested using `DateTimeImmutable` for date handling. I rejected
it because it is more complex than this assignment needs, and the textbook uses `date()` and `filemtime()`.

## Improved
- Copilot suggested checking `is_file()` before `filemtime()`, but its fallback printed the current time as "Last modified", which would be inaccurate. I kept the check but changed the fallback to "Unknown".

## echo and htmlspecialchars()
Copilot often writes `echo "<p>Hello World</p>";`. That is fine for
static text I typed myself. Anything that comes from a user or from
`$_GET`, `$_POST`, or `$_SERVER` must go through `htmlspecialchars()`
so it prints as text instead of running as code.

## require() vs include()
This is copilots definition

require() vs include() in PHP
Both are used to pull in another PHP file, but they behave differently when something goes wrong.

include()

Tries to include the file.
If the file is missing, PHP throws a warning, but the script keeps running.
Good for optional files, such as a footer or a feature that can fail gracefully.
require()

Tries to include the file and treats it as essential.
If the file is missing, PHP throws a fatal error and stops script execution.
Good for core files like configuration, database connection setup, or required classes.
Example:


There is also:

include_once() and require_once()
These make sure the file is included only once, preventing duplicate code or redeclaration errors.
Quick rule
Use require() for files your app cannot run without.
Use include() for files that are nice to have but not critical.

The textbook says the same thing about these functions.