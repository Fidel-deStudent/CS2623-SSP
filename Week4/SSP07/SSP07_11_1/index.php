<?php
declare(strict_types=1);

/*
 * index.php - CS2623 SSP07 Exercise 11-1 (Ch11) - Author: Fraire, Fidel
 * Self-processing form that demonstrates arrays: [] short syntax, foreach,
 * count(), sort(), in_array() (strict), array_map() and array_filter()
 * with arrow functions, and an array of arrays that simulates DB rows.
 */

// Unsorted on purpose so the "Sort A→Z" button visibly changes the order
$fruits = ['mango', 'apple', 'kiwi', 'elderberry', 'banana', 'date', 'cherry', 'fig'];

// The search is read raw (no sanitize filter) and escaped only on output.
// FILTER_SANITIZE_SPECIAL_CHARS here plus htmlspecialchars() below would
// double-encode the value ("&" -> "&amp;amp;").
$search  = trim(filter_input(INPUT_POST, 'search') ?? '');
$action  = filter_input(INPUT_POST, 'action') ?? '';
$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'search') {
    if ($search === '') {
        $error = 'Please enter a fruit name to search for.';
    } elseif (!preg_match('/^[a-zA-Z]+$/', $search)) {
        // Validate: letters only; anything else is an error and no search runs
        $error = 'Search must contain letters only (A–Z), no digits, spaces or symbols.';
    } else {
        // The list is lowercase, so compare in lowercase; strict === comparison
        $found   = in_array(strtolower($search), $fruits, true);
        $message = $found ? "✓ Found: $search" : "✗ Not found: $search";
    }
}

// sort $fruits alphabetically (sort() works in place, so sort a copy
// and keep the original order for the "Original List" section)
$sorted = $fruits;
sort($sorted);
$show_sorted = $_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'sort';

// array_map with arrow function (PHP 7.4+)
$upper = array_map(fn($f) => strtoupper($f), $fruits);

// filter fruits shorter than 6 chars (keep names with 6 or more letters)
$long_fruits = array_filter($fruits, fn($f) => strlen($f) >= 6);

// arrays of arrays — simulates a DB result set
$catalog = [
    ['id' => 1, 'name' => 'Apple',        'price' => 1.29],
    ['id' => 2, 'name' => 'Banana',       'price' => 0.49],
    ['id' => 3, 'name' => 'Cherry',       'price' => 2.99],
    ['id' => 4, 'name' => 'Dragon Fruit', 'price' => 4.99],
    ['id' => 5, 'name' => 'Elderberry',   'price' => 3.49],
];

$page_title = 'CS2623 SSP07 Ex 11-1: Fraire';
$hub        = 'SSP07';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>SSP07 Exercise 11-1 — Fruit Array Demo</h2>

    <!-- Search form: posts back to this page. Two buttons share one form;
         the clicked button's name/value tells PHP which action to run. -->
    <form method="post" action="index.php">
        <fieldset>
            <legend>Search the fruit list</legend>
            <div class="form-row">
                <label for="search">Search fruit:</label>
                <input type="text" id="search" name="search"
                       value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <button type="submit" name="action" value="search">Search</button>
            <button type="submit" name="action" value="sort">Sort A→Z</button>
        </fieldset>
    </form>

    <?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($message !== ''): ?>
    <p class="info"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <div class="list-columns">
        <div class="list-box">
            <h3>Original List (<?= count($fruits) ?> items)</h3>
            <ul>
                <?php foreach ($fruits as $f): ?>
                <li><?= htmlspecialchars($f, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if ($show_sorted): ?>
        <div class="list-box">
            <h3>Sorted A→Z with sort() (<?= count($sorted) ?> items)</h3>
            <ul>
                <?php foreach ($sorted as $s): ?>
                <li><?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="list-box">
            <h3>Uppercase (array_map + arrow fn)</h3>
            <ul>
                <?php foreach ($upper as $u): ?>
                <li><?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="list-box">
            <h3>6+ Letters (array_filter, <?= count($long_fruits) ?> items)</h3>
            <ul>
                <?php foreach ($long_fruits as $lf): ?>
                <li><?= htmlspecialchars($lf, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <h2>Catalog (array of arrays, <?= count($catalog) ?> rows)</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th class="money">Price</th>
        </tr>
        <?php foreach ($catalog as $row): ?>
        <tr>
            <td><?= (int)$row['id'] ?></td>
            <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="money">$<?= number_format((float)$row['price'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
