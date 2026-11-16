<?php
declare(strict_types=1);

/*
 * index.php - CS2623 SSP08 Exercise 03-1 (Ch03) - Author: Fraire, Fidel
 * Self-processing page that demonstrates control structures: for, while
 * and do-while loops, an if/elseif chain and a switch statement. Every
 * function returns its value; only the view echoes, through htmlspecialchars().
 */

// ── Functions (return values; never echo directly) ─────────────

function fn_fizzbuzz(int $n): string
{
    if ($n % 15 === 0)    return 'FizzBuzz';
    elseif ($n % 3 === 0) return 'Fizz';
    elseif ($n % 5 === 0) return 'Buzz';
    else                  return (string)$n;
}

function fn_dayOfWeek(int $dow): string
{
    // MUST use if/elseif — do NOT use match() for this exercise
    if     ($dow === 0) return 'Sunday';
    elseif ($dow === 1) return 'Monday';
    elseif ($dow === 2) return 'Tuesday';
    elseif ($dow === 3) return 'Wednesday';
    elseif ($dow === 4) return 'Thursday';
    elseif ($dow === 5) return 'Friday';
    else                return 'Saturday';
}

function fn_gradeLabel(int $score): string
{
    // MUST use switch — do NOT use match() for this exercise
    switch (true) {
        case $score >= 90: return 'A';
        case $score >= 80: return 'B';
        case $score >= 70: return 'C';
        case $score >= 60: return 'D';
        default:           return 'F';
    }
}

// Reads one integer field. filter_input() returns null when the field is
// missing and false when it is not a whole number, so both fall back to
// $default (a bare "?? $default" would let false through and cast it to 0).
function fn_postInt(string $name, int $default): int
{
    $value = filter_input(INPUT_POST, $name, FILTER_VALIDATE_INT);
    return is_int($value) ? $value : $default;
}

// ── POST handling ───────────────────────────────────────────────
const MAX_REPEAT = 20;

$is_post = $_SERVER['REQUEST_METHOD'] === 'POST';

$start = fn_postInt('start', 1);
$end   = fn_postInt('end', 20);
$score = fn_postInt('score', -1);
$times = fn_postInt('times', 3);

// The word is read raw and escaped only on output; FILTER_SANITIZE_SPECIAL_CHARS
// plus htmlspecialchars() would double-encode it ("&" -> "&amp;amp;").
$word = trim(filter_input(INPUT_POST, 'word') ?? '');

// Keep every number inside the range the form allows
if ($start < 1)   $start = 1;
if ($start > 100) $start = 100;
if ($end < 1)     $end = 1;
if ($end > 100)   $end = 100;
if ($score > 100) $score = 100;
if ($times < 1)   $times = 1;
if ($times > MAX_REPEAT) $times = MAX_REPEAT;

// Start after End is an error: show the message instead of the results
$error = '';
if ($is_post && $start > $end) {
    $error = "Start ($start) cannot be greater than End ($end). Please fix the range and run again.";
}

$page_title = 'CS2623 SSP08 Ex 03-1: Fraire';
$hub        = 'SSP08';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>SSP08 Exercise 03-1 — Control Structures Demo</h2>

    <!-- Form: posts back to this page -->
    <form method="post" action="index.php">
        <fieldset>
            <legend>Number range (FizzBuzz and Count Down)</legend>
            <div class="form-row">
                <label for="start">Start:</label>
                <input type="number" id="start" name="start" min="1" max="100"
                       value="<?= (int)$start ?>">
            </div>
            <div class="form-row">
                <label for="end">End:</label>
                <input type="number" id="end" name="end" min="1" max="100"
                       value="<?= (int)$end ?>">
            </div>
        </fieldset>
        <fieldset>
            <legend>Grade lookup</legend>
            <div class="form-row">
                <label for="score">Score (0-100):</label>
                <input type="number" id="score" name="score" min="0" max="100"
                       value="<?= ($score >= 0 ? (int)$score : '') ?>">
            </div>
            <p class="hint">Leave blank to skip the grade lookup.</p>
        </fieldset>
        <fieldset>
            <legend>Repeat a word</legend>
            <div class="form-row">
                <label for="word">Word:</label>
                <input type="text" id="word" name="word" maxlength="30"
                       value="<?= htmlspecialchars($word, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-row">
                <label for="times">Times (1-<?= MAX_REPEAT ?>):</label>
                <input type="number" id="times" name="times" min="1" max="<?= MAX_REPEAT ?>"
                       value="<?= (int)$times ?>">
            </div>
            <p class="hint">Leave the word blank to skip this section.</p>
        </fieldset>
        <button type="submit">Run</button>
    </form>

<?php if ($is_post && $error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>

<?php elseif ($is_post): ?>

    <h3>FizzBuzz (for loop, <?= (int)$start ?>–<?= (int)$end ?>)</h3>
    <ul class="inline-list">
    <?php for ($i = $start; $i <= $end; $i++): ?>
        <li><?= htmlspecialchars(fn_fizzbuzz($i), ENT_QUOTES, 'UTF-8') ?></li>
    <?php endfor; ?>
    </ul>

    <h3>Count Down (do-while loop, <?= (int)$end ?>–<?= (int)$start ?>)</h3>
    <ul class="inline-list">
    <?php $n = $end; do { ?>
        <li><?= (int)$n ?></li>
    <?php $n--; } while ($n >= $start); ?>
    </ul>

    <h3>Day Names (while loop)</h3>
    <ul>
    <?php $d = 0; while ($d <= 6): ?>
        <li><?= htmlspecialchars(fn_dayOfWeek($d), ENT_QUOTES, 'UTF-8') ?></li>
    <?php $d++; endwhile; ?>
    </ul>

    <h3>Grade Lookup (switch)</h3>
    <?php if ($score >= 0): ?>
    <p>Score <?= (int)$score ?>:
        Grade <strong><?= htmlspecialchars(fn_gradeLabel($score), ENT_QUOTES, 'UTF-8') ?></strong>
    </p>
    <?php else: ?>
    <p>No score entered.</p>
    <?php endif; ?>

    <h3>Repeat Word (while loop, <?= (int)$times ?> times)</h3>
    <?php if ($word !== ''): ?>
    <ol>
    <?php $r = 1; while ($r <= $times): ?>
        <li><?= htmlspecialchars($word, ENT_QUOTES, 'UTF-8') ?></li>
    <?php $r++; endwhile; ?>
    </ol>
    <?php else: ?>
    <p>No word entered.</p>
    <?php endif; ?>

    <h3>Multiplication Table (do-while)</h3>
    <table class="times">
    <?php $row = 1; do { ?>
        <tr>
        <?php $col = 1; do { ?>
            <td><?= (int)$row * (int)$col ?></td>
        <?php $col++; } while ($col <= 5); ?>
        </tr>
    <?php $row++; } while ($row <= 5); ?>
    </table>

<?php endif; ?>
<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
