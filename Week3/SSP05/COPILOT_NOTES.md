# COPILOT_NOTES.md – Week 3

## Ch06 – Debugging

| # | Situation | Copilot Suggestion | Decision | Notes |
|---|-----------|-------------------|----------|-------|
| 1 | Needed to see what the form sent | `print_r($_POST)` wrapped in `<pre>` inside an `if` on `REQUEST_METHOD === 'POST'` | ACCEPTED | `<pre>` keeps the array readable and the POST check stops it running on the first page load. `print_r` doesn't show types, though, so `var_dump` is better when I need to see that `age` arrives as a string. Removed before committing. |
| 2 | Gender showed "not specified" after I picked one (I had renamed the radio to `name="gendr"` as a test) | The radio `name` and the `filter_input(INPUT_POST, 'gender')` key must match. Change the input back to `name="gender"` | ACCEPTED | PHP only finds a field when the HTML `name` and the key match exactly. With `gendr`, `filter_input()` returned NULL, so my `?? 'not specified'` default kicked in instead of a warning. Changed the name back to `gender` and the value came through. |

## Ch07 – Form Data

| # | File | Copilot Suggestion | Decision | Notes |
|---|------|-------------------|----------|-------|
| 3 | SSP05_1.php | `filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING)` for every text field | REJECTED | `FILTER_SANITIZE_STRING` is deprecated as of PHP 8.1 and throws a warning on XAMPP. It also strips characters out of what the user typed. I read the raw value with `filter_input()` and escape it with `htmlspecialchars()` only when it's output. |
| 4 | SSP05_1.php | `filter_input()` calls with no default value | IMPROVED | `filter_input()` returns NULL when a field isn't sent at all, e.g. no gender radio picked. Added `?? ''` (and `?? 'not specified'` for gender) so the page never works with NULL. |
| 5 | SSP05_1.php | `filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)` | IMPROVED | Sanitizing only removes illegal characters, so `abc` would still pass as an email. Changed to `FILTER_VALIDATE_EMAIL`, which returns false for an invalid address so I can show "(invalid)". |
| 6 | SSP05_2.php | `filter_input(INPUT_POST, 'options', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)` | IMPROVED | `FILTER_REQUIRE_ARRAY` was right, but it used the deprecated filter and had no fallback. With no boxes ticked it returns NULL and `foreach` warns. Switched to `FILTER_SANITIZE_SPECIAL_CHARS` and added `?? []`. Tested by submitting with no boxes ticked: the summary showed "None" with no warning. |

**Copilot Chat – "What is the difference between filter_input and $_POST in PHP?"**

> $_POST and filter_input() both read values submitted with an HTTP POST request, but they work differently:
>
> $_POST is PHP’s superglobal array. You access values directly, for example: $_POST['email'].
>
> filter_input() retrieves a value from a specific input source and can apply a filter while retrieving it, for example: filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL).
>
> The filter is useful for validating or sanitizing input, but it does not replace all checks your application needs. Validate data according to your requirements, and escape it for the context where you use it (such as HTML or SQL).
>
> One practical difference: filter_input() reads the original request data and generally won’t reflect later changes made to $_POST. Also, if the field is absent, it returns null; an invalid value with a validation filter returns false.

## Ch21 – Security Awareness

| # | Concept | Observation | Notes |
|---|---------|-------------|-------|
| 7 | XSS | Asked Copilot Chat "Is this page safe from XSS?" on SSP05_1.php. It found no exploitable XSS because every submitted value is escaped before it's displayed, but it said it hadn't checked the included header/footer files. | Every echoed form value goes through `htmlspecialchars($x, ENT_QUOTES, 'UTF-8')`, including values put back into `value=""` and the textarea, and `nl2br()` runs after escaping, not before. I checked the includes myself; they escape their output too. |
| 8 | Future: sessions | Asked Copilot Chat what `session_regenerate_id(true)` does. It gives the session a new ID and keeps the data, while `true` deletes the old session. It's called right after a successful login to stop session fixation, and it returns false if regeneration fails. | session_regenerate_id(true) will be needed after login in a future MySQL week |
