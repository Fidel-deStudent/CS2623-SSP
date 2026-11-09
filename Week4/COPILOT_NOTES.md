# CS-2623 Week 4 — GitHub Copilot Awareness Log

## SSP07 — Ex 11-1 (Arrays)

| Suggestion | Action | Reason |
|---|---|---|
| `array()` for `$fruits` declaration | Improved | Changed to `[]` short syntax per PHP 8 standard |
| `in_array($search, $fruits)` — no 3rd arg | Improved | Added `true` for strict type comparison |
| `array_filter` with anonymous function | Improved | Replaced with arrow function `fn($f) => strlen($f) >= 6` |
| `foreach` echo without escaping | Rejected | Added `htmlspecialchars()` on ALL echoed values — XSS prevention |
| Price output with direct echo | Improved | Wrapped with `number_format()` for currency formatting |

## SSP07 — Ex 15-1 (Regex/Exceptions)

| Suggestion | Action | Reason |
|---|---|---|
| Custom email regex pattern | Rejected | `filter_var(FILTER_VALIDATE_EMAIL)` is RFC-compliant; custom regex is fragile |
| `strip_tags()` in output section | Rejected | `strip_tags()` ≠ XSS protection; replaced with `htmlspecialchars()` |
| `addslashes()` for DB safety | Rejected | Must use PDO prepared statements; `addslashes()` is insufficient |
| `catch (Exception $e)` as first catch | Improved | Reordered: `FormatException` first, then `ValidationException` (its parent), `DatabaseException`, and `RuntimeException` last |
| `htmlspecialchars` on `$e->getMessage()` | Accepted | Copilot correctly applied escaping to exception message output |

## SSP06 — Ex 21-2 (Auth)

| Suggestion | Action | Reason |
|---|---|---|
| `md5()` or `sha1()` for password hashing | Rejected | Insecure — use `password_hash(PASSWORD_DEFAULT)` only |
| `session_start()` without `regenerate_id` | Improved | Added `session_regenerate_id(true)` after successful login |
| Direct `$_POST['username']` access | Improved | Replaced with `filter_input(INPUT_POST, ...)` for sanitization |
| Hardcoded comparison: `$pw === 'secret'` | Rejected | Must use `password_verify()` with hashed password |
| `session_destroy()` on logout | Accepted | Copilot correctly used `session_destroy()` + redirect pattern |

## SSP06 — Ex 23-1 (Shopping Cart)

| Suggestion | Action | Reason |
|---|---|---|
| `$_SESSION['cart'] = $_SESSION['cart'] ?? []` | Improved | Shortened to `??=` assignment operator (PHP 8) |
| `(int)$_POST['product_id']` direct cast | Improved | Replaced with `filter_input(FILTER_VALIDATE_INT)` |
| Price output with raw echo | Improved | Wrapped with `number_format($price, 2)` for currency format |
| Session without `session_start()` | Rejected | Copilot sometimes omits `session_start()` — always required |
| No auth check on cart page | Improved | Added `require 'auth.php'` to restrict cart to logged-in users |
