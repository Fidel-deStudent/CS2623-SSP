<?php
declare(strict_types=1);

/*
 * index.php - CS2623 SSP07 Exercise 15-1 (Ch15) - Author: Fraire, Fidel
 * Self-processing contact form. filter_var() validates email, URL and
 * integer fields; preg_match() handles phone, ZIP and product code, which
 * filter_var() has no validator for. Errors are thrown as custom exceptions
 * and caught most-specific first in a try/catch/finally chain.
 */

// Custom exception hierarchy:
//   RuntimeException
//   ├── ValidationException      (bad form input)
//   │   └── FormatException      (product code doesn't match AA9999)
//   └── DatabaseException        (save failed)
class ValidationException extends RuntimeException {}
class FormatException extends ValidationException {}
class DatabaseException extends RuntimeException {}

/**
 * Validates the contact data. Collects every error first so the user sees
 * them all at once, then throws FormatException when the product code is
 * one of the problems, otherwise ValidationException.
 */
function validateContact(array $data): array
{
    $errors = [];

    // Email — use filter_var, NOT a custom regex
    if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    // Website — filter_var(FILTER_VALIDATE_URL). It also accepts schemes like
    // javascript: and ftp:, so require http or https as well.
    $website = $data['website'] ?? '';
    $scheme  = strtolower((string)parse_url($website, PHP_URL_SCHEME));
    if (filter_var($website, FILTER_VALIDATE_URL) === false || !in_array($scheme, ['http', 'https'], true)) {
        $errors[] = 'Website must be a full URL starting with http:// or https:// (e.g. https://www.occc.edu).';
    }

    // Quantity — filter_var(FILTER_VALIDATE_INT) returns false, not null, on
    // failure. Compare with === false because a valid 0 would also be falsy.
    $quantity = filter_var($data['quantity'] ?? '', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 99],
    ]);
    if ($quantity === false) {
        $errors[] = 'Quantity must be a whole number from 1 to 99.';
    }

    // Phone — filter_var has no phone validator; use preg_match
    $phone = $data['phone'] ?? '';
    if (!preg_match('/^\d{3}-\d{3}-\d{4}$/', $phone)) {
        $errors[] = 'Phone must be in format 555-555-5555.';
    }

    // ZIP code
    $zip = $data['zip'] ?? '';
    if (!preg_match('/^\d{5}(-\d{4})?$/', $zip)) {
        $errors[] = 'ZIP must be 5 digits or ZIP+4 (e.g. 74103 or 74103-1234).';
    }

    // Product code — two uppercase letters followed by four digits
    $badProductCode = !preg_match('/^[A-Z]{2}\d{4}$/', $data['product_code'] ?? '');
    if ($badProductCode) {
        $errors[] = 'Product code must be 2 uppercase letters + 4 digits (e.g. GT1234).';
    }

    if ($errors) {
        $message = implode(' | ', $errors);
        if ($badProductCode) {
            throw new FormatException($message);
        }
        throw new ValidationException($message);
    }

    $data['quantity'] = $quantity;
    return $data;
}

/**
 * Pretends to save the contact. There is no database yet (that comes in a
 * later week), so a checkbox on the form can force a DatabaseException to
 * show the third catch block working.
 */
function saveContact(array $data, bool $simulateFailure): void
{
    if ($simulateFailure) {
        throw new DatabaseException('Could not connect to the contacts database (simulated).');
    }
}

// Sticky form values; empty on the first (GET) load
$fields = ['email', 'website', 'quantity', 'phone', 'zip', 'product_code', 'notes', 'message'];
$raw    = array_fill_keys($fields, '');
$simulate_db_error = false;

$result     = null;
$errMsg     = null;
$errTitle   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw values (no sanitize filter) and trim them. Validation happens in
    // validateContact(); escaping happens only when values are output.
    // FILTER_SANITIZE_SPECIAL_CHARS here would turn o'brien@x.com into
    // o&#39;brien@x.com (which then fails email validation) and double-encode
    // everything echoed back with htmlspecialchars().
    foreach ($fields as $field) {
        $raw[$field] = trim(filter_input(INPUT_POST, $field) ?? '');
    }
    $simulate_db_error = filter_input(INPUT_POST, 'simulate_db_error') === '1';

    try {
        $validated = validateContact($raw);
        saveContact($validated, $simulate_db_error);
        $result = $validated;
    } catch (FormatException $e) {          // most specific first
        $errTitle = 'Format error (FormatException)';
        $errMsg   = $e->getMessage();
    } catch (ValidationException $e) {      // then its parent class
        $errTitle = 'Validation error (ValidationException)';
        $errMsg   = $e->getMessage();
    } catch (DatabaseException $e) {        // sibling of ValidationException
        $errTitle = 'Database error (DatabaseException)';
        $errMsg   = $e->getMessage();
    } catch (RuntimeException $e) {         // broadest last
        $errTitle = 'System error (RuntimeException)';
        $errMsg   = $e->getMessage();
    } finally {
        // runs regardless — good for logging. Apollo runs UTC, so log Central time.
        $now = new DateTime('now', new DateTimeZone('America/Chicago'));
        error_log('SSP07 contact form processed at ' . $now->format('Y-m-d H:i:s T'));
    }
}

$page_title = 'CS2623 SSP07 Ex 15-1: Fraire';
$hub        = 'SSP07';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>SSP07 Exercise 15-1 — Contact Form Validation</h2>

    <?php if ($errMsg !== null): ?>
    <div class="error">
        <?= htmlspecialchars($errTitle, ENT_QUOTES, 'UTF-8') ?>:
        <ul>
            <?php foreach (explode(' | ', $errMsg) as $line): ?>
            <li><?= htmlspecialchars($line, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($result !== null): ?>
    <p class="success">✓ All fields valid — ready to save.</p>
    <table>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= htmlspecialchars($result['email'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <td>Website</td>
            <td><?= htmlspecialchars($result['website'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <td>Quantity</td>
            <td><?= (int)$result['quantity'] ?></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><?= htmlspecialchars($result['phone'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <td>ZIP</td>
            <td><?= htmlspecialchars($result['zip'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <td>Product Code</td>
            <td><?= htmlspecialchars($result['product_code'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <!-- Escape FIRST, then nl2br(): the other order would let
             htmlspecialchars() turn the <br> tags into visible text -->
        <tr>
            <td>Notes</td>
            <td><?= nl2br(htmlspecialchars($result['notes'], ENT_QUOTES, 'UTF-8')) ?></td>
        </tr>
        <tr>
            <td>Message</td>
            <td><?= nl2br(htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8')) ?></td>
        </tr>
    </table>
    <?php endif; ?>

    <form method="post" action="index.php" novalidate>
        <fieldset>
            <legend>Contact (filter_var)</legend>
            <div class="form-row">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email"
                       value="<?= htmlspecialchars($raw['email'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-row">
                <label for="website">Website:</label>
                <input type="text" id="website" name="website" placeholder="https://"
                       value="<?= htmlspecialchars($raw['website'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-row">
                <label for="quantity">Quantity (1–99):</label>
                <input type="text" id="quantity" name="quantity"
                       value="<?= htmlspecialchars($raw['quantity'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Formats (preg_match)</legend>
            <div class="form-row">
                <label for="phone">Phone (555-555-5555):</label>
                <input type="text" id="phone" name="phone"
                       value="<?= htmlspecialchars($raw['phone'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-row">
                <label for="zip">ZIP:</label>
                <input type="text" id="zip" name="zip"
                       value="<?= htmlspecialchars($raw['zip'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-row">
                <label for="product_code">Product Code (AA9999):</label>
                <input type="text" id="product_code" name="product_code"
                       value="<?= htmlspecialchars($raw['product_code'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Notes &amp; Message</legend>
            <div class="form-row">
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" rows="4" cols="40"><?= htmlspecialchars($raw['notes'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="form-row">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" cols="40"><?= htmlspecialchars($raw['message'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="form-row">
                <label>
                    <input type="checkbox" name="simulate_db_error" value="1"
                           <?= $simulate_db_error ? 'checked' : '' ?>>
                    Simulate a database error
                </label>
            </div>
            <p class="hint">Tick to throw a DatabaseException after validation passes.</p>
        </fieldset>

        <button type="submit">Validate</button>
    </form>
<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
