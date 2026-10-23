<?php
declare(strict_types=1);

/*
 * ex1_strings.php - CS2623 SSP04 Exercise 1 (Ch09)
 * Demonstrates PHP string functions on a sample product description.
 * No form submission: every value is computed here and escaped on output.
 */

/**
 * Escape a value for safe HTML output.
 *
 * @param string $val Text to escape.
 * @return string Escaped text.
 */
function h(string $val): string {
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

// Sample data
$description = 'Learn PHP the modern way with this hands-on course book & workbook.';
$price       = 49.5;
$units_sold  = 1250000;

// str_replace(): swap one word for another
$replaced = str_replace('hands-on', 'practical', $description);

// Use str_contains() to check if $description contains "PHP"
$has_php = str_contains($description, 'PHP');

// strtolower() / strtoupper(): change case
$lower = strtolower($description);
$upper = strtoupper($description);

// strlen(): character count
$length = strlen($description);

// sprintf(): price with two decimal places
$price_text = sprintf('$%.2f', $price);

// number_format(): large number with thousands separators
$units_text = number_format($units_sold);

// substr(): first 20 characters
$excerpt = substr($description, 0, 20);

// Rows for the results table: [function call, input, output]
$rows = [
    ['str_replace("hands-on", "practical", $description)', '"' . $description . '"', '"' . $replaced . '"'],
    ['str_contains($description, "PHP")',          '"' . $description . '"', $has_php ? 'true' : 'false'],
    ['strtolower($description)',                   '"' . $description . '"', '"' . $lower . '"'],
    ['strtoupper($description)',                   '"' . $description . '"', '"' . $upper . '"'],
    ['strlen($description)',                       '"' . $description . '"', (string)$length],
    ['sprintf("$%.2f", ' . $price . ')',           (string)$price,           '"' . $price_text . '"'],
    ['number_format(' . $units_sold . ')',         (string)$units_sold,      '"' . $units_text . '"'],
    ['substr($description, 0, 20)',                '"' . $description . '"', '"' . $excerpt . '"'],
];

// Shared header and footer from SSP03 (same look as the Week 1 pages)
$page_title = 'CS2623 SSP04 Ex 1: Fraire';
require_once __DIR__ . '/../SSP03/PHP/include_header.php';
?>
    <?php require_once __DIR__ . '/PHP/include_exerciseNav.php'; ?>
    <h2><?php echo h('SSP04 Exercise 1 — PHP String Functions'); ?></h2>

    <p><?php echo h('Original description:'); ?>
        <strong><?php echo h($description); ?></strong></p>

    <table>
        <tr>
            <th><?php echo h('Function'); ?></th>
            <th><?php echo h('Input'); ?></th>
            <th><?php echo h('Output'); ?></th>
        </tr>
        <?php foreach ($rows as [$function, $input, $output]): ?>
        <tr>
            <td><code><?php echo h($function); ?></code></td>
            <td><?php echo h($input); ?></td>
            <td><?php echo h($output); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php require_once __DIR__ . '/../SSP03/PHP/include_footer.php'; ?>
