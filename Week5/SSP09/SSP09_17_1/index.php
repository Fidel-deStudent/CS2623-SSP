<?php
declare(strict_types=1);

/*
 * index.php - CS2623 SSP09 Exercise 17-1 (Ch17) - Author: Fraire, Fidel
 * Self-processing form that demonstrates string functions (PHP 8
 * str_contains / str_starts_with / str_ends_with, str_replace, substr),
 * number formatting (number_format, round, ceil, sprintf, random_int) and
 * DateTime + DateTimeZone date math (format, diff, date_diff).
 */

$tz = new DateTimeZone('America/Chicago');   // Apollo UTC fix

// ── POST data ──────────────────────────────────────────────────
// Text fields are read raw and escaped only on output. With
// FILTER_SANITIZE_SPECIAL_CHARS, "Tom's" would become "Tom&#39;s": strlen()
// would count 9 characters instead of 5, and htmlspecialchars() would
// double-encode it on the page.
$is_post   = $_SERVER['REQUEST_METHOD'] === 'POST';
$inputText = filter_input(INPUT_POST, 'text')   ?? '';
$searchFor = trim(filter_input(INPUT_POST, 'search') ?? '');

// FILTER_VALIDATE_FLOAT returns false for bad input, so check the type
// instead of relying on ?? (which only replaces null)
$amountRaw = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
$inputNum  = is_float($amountRaw) ? $amountRaw : 0.0;

// ── String operations ──────────────────────────────────────────
$trimmed    = trim($inputText);
$lower      = strtolower($trimmed);
$upper      = strtoupper($trimmed);
$length     = strlen($trimmed);
$contains   = $searchFor !== '' ? str_contains(strtolower($trimmed), strtolower($searchFor)) : null;
$startsWith = $searchFor !== '' ? str_starts_with(strtolower($trimmed), strtolower($searchFor)) : null;
$endsWith   = $searchFor !== '' ? str_ends_with(strtolower($trimmed), strtolower($searchFor)) : null;

// Replace every vowel (both cases) with *
$vowels   = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
$noVowels = str_replace($vowels, '*', $trimmed);

// First 10 characters; shorter text is shown whole
$first10 = $length > 10 ? substr($trimmed, 0, 10) : $trimmed;

// Word count: empty text is 0 words (explode(' ', '') returns [''], a count of 1),
// and runs of spaces are collapsed first so "a   b" counts as 2, not 4
$wordCount = $trimmed === ''
    ? 0
    : count(explode(' ', preg_replace('/\s+/', ' ', $trimmed) ?? $trimmed));

// ── Number operations ──────────────────────────────────────────
$formatted = number_format($inputNum, 2);
$currency  = ($inputNum < 0 ? '-$' . number_format(abs($inputNum), 2) : '$' . $formatted);
$padded    = sprintf('%10.2f', $inputNum);   // right-aligned in 10 characters
$randNum   = random_int(1, 100);             // secure; never use rand()
$rounded   = round($inputNum, 0);
$ceiling   = (int)ceil($inputNum);

// ── Date operations ────────────────────────────────────────────
$now            = new DateTime('now', $tz);
$today          = new DateTime('today', $tz);       // midnight, for whole-day math
$formatted_date = $now->format('l, F j, Y');        // e.g. Monday, November 9, 2026
$formatted_time = $now->format('g:i:s A T');        // e.g. 3:45:30 PM CST
$tomorrow       = (new DateTime('tomorrow', $tz))->format('Y-m-d');

// Next Christmas: this year's, or next year's once Dec 25 has passed
$xmas = new DateTime($today->format('Y') . '-12-25', $tz);
if ($xmas < $today) {
    $xmas->modify('+1 year');
}
$daysUntilXmas = $today->diff($xmas)->days;

// Days since January 1 of the current year, with the date_diff() function
$jan1           = new DateTime($today->format('Y') . '-01-01', $tz);
$daysSinceJan1  = date_diff($jan1, $today)->days;

$page_title = 'CS2623 SSP09 Ex 17-1: Fraire';
$hub        = 'SSP09';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>SSP09 Exercise 17-1 — String, Number &amp; Date Functions</h2>

    <form method="post" action="index.php">
        <fieldset>
            <legend>Enter some values</legend>
            <div class="form-row">
                <label for="text">Enter text:</label>
                <input type="text" id="text" name="text" maxlength="200"
                       value="<?= htmlspecialchars($inputText, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-row">
                <label for="search">Search within text:</label>
                <input type="text" id="search" name="search" maxlength="50"
                       value="<?= htmlspecialchars($searchFor, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-row">
                <label for="amount">Dollar amount:</label>
                <input type="number" id="amount" name="amount" step="0.01"
                       value="<?= $is_post ? htmlspecialchars((string)$inputNum, ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
            <button type="submit">Analyze</button>
        </fieldset>
    </form>

<?php if ($is_post): ?>
    <div class="list-columns">
        <div class="list-box">
            <h3>String Results</h3>
            <ul>
                <li>Original: <?= htmlspecialchars($inputText, ENT_QUOTES, 'UTF-8') ?></li>
                <li>Lowercase: <?= htmlspecialchars($lower, ENT_QUOTES, 'UTF-8') ?></li>
                <li>Uppercase: <?= htmlspecialchars($upper, ENT_QUOTES, 'UTF-8') ?></li>
                <li>Trimmed length: <?= (int)$length ?> characters</li>
                <li>Word count: <?= (int)$wordCount ?></li>
                <li>Vowels → *: <?= htmlspecialchars($noVowels, ENT_QUOTES, 'UTF-8') ?></li>
                <!-- ENT_SUBSTITUTE: substr() counts bytes, so it can cut an
                     accented letter in half; show � instead of an empty string -->
                <li>First 10 characters: <?= htmlspecialchars($first10, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
                <?php if ($contains !== null): ?>
                <li>Contains "<?= htmlspecialchars($searchFor, ENT_QUOTES, 'UTF-8') ?>": <?= $contains ? 'Yes' : 'No' ?></li>
                <li>Starts with it: <?= $startsWith ? 'Yes' : 'No' ?></li>
                <li>Ends with it: <?= $endsWith ? 'Yes' : 'No' ?></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="list-box">
            <h3>Number Results</h3>
            <ul>
                <li>Formatted: <?= htmlspecialchars($currency, ENT_QUOTES, 'UTF-8') ?></li>
                <li>Padded (10 chars): [<code class="padded"><?= htmlspecialchars($padded, ENT_QUOTES, 'UTF-8') ?></code>]</li>
                <li>Rounded: <?= htmlspecialchars(number_format($rounded, 0), ENT_QUOTES, 'UTF-8') ?></li>
                <li>Ceiling: <?= (int)$ceiling ?></li>
                <li>Random (1-100): <?= (int)$randNum ?></li>
            </ul>
        </div>
    </div>
<?php endif; ?>

    <h3>Date &amp; Time (US Central / Apollo-safe)</h3>
    <ul>
        <li>Today: <?= htmlspecialchars($formatted_date, ENT_QUOTES, 'UTF-8') ?></li>
        <li>Time: <?= htmlspecialchars($formatted_time, ENT_QUOTES, 'UTF-8') ?></li>
        <li>Tomorrow: <?= htmlspecialchars($tomorrow, ENT_QUOTES, 'UTF-8') ?></li>
        <li>Days since January 1, <?= htmlspecialchars($jan1->format('Y'), ENT_QUOTES, 'UTF-8') ?>: <?= (int)$daysSinceJan1 ?></li>
        <li>Days until Christmas: <?= (int)$daysUntilXmas ?></li>
    </ul>

<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
