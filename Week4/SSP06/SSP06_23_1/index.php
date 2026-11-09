<?php
declare(strict_types=1);

/*
 * index.php - CS2623 SSP06 Exercise 23-1 (Ch23) - Author: Fraire, Fidel
 * Guitar Shop catalog + shopping cart kept in $_SESSION['cart'] as
 * [product_id => quantity]. Login is required (Ex 21-2 auth.php).
 * Every POST redirects back here (Post/Redirect/Get) so a page refresh
 * doesn't add the same item again.
 */

session_start();
require '../SSP06_21_2/auth.php';    // must be logged in to use the cart

// Product catalog — simulates a DB query result (keyed by product ID)
$products = [
    1 => ['id' => 1, 'name' => 'Fender Stratocaster',  'price' => 499.99],
    2 => ['id' => 2, 'name' => 'Gibson Les Paul',      'price' => 799.99],
    3 => ['id' => 3, 'name' => 'Martin D-28 Acoustic', 'price' => 1299.99],
    4 => ['id' => 4, 'name' => 'Ibanez RG550',         'price' => 299.99],
];

// Initialize cart
$_SESSION['cart'] ??= [];   // PHP 8 null coalescing assignment

// Handle "Add to Cart" and "Remove one" POSTs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // FILTER_VALIDATE_INT returns false for "abc" or "1.5", null if missing
    $id     = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action') ?? 'add';

    if ($id !== null && $id !== false && isset($products[$id])) {
        if ($action === 'remove') {
            // Decrement; drop the line once the quantity reaches 0
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]--;
                if ($_SESSION['cart'][$id] <= 0) {
                    unset($_SESSION['cart'][$id]);
                }
            }
        } else {
            // Increment quantity; start at 0 if not in cart
            $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
        }
    }
    header('Location: index.php');
    exit;
}

// Calculate cart total and item count
$total = 0.0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    if (isset($products[$pid])) {
        $total += $products[$pid]['price'] * $qty;
    }
}
$item_count = array_sum($_SESSION['cart']);

$page_title = 'CS2623 SSP06 Ex 23-1 Guitar Shop: Fraire';
$hub        = 'SSP06';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>Guitar Shop — Cart (<?= (int)$item_count ?> item<?= $item_count === 1 ? '' : 's' ?>)</h2>

    <nav class="sub-nav">
        <a href="../SSP06_21_2/dashboard.php">Dashboard</a> |
        <a href="../SSP06_21_2/profile.php">Profile</a> |
        <a href="#cart">View Cart</a> |
        <a href="../SSP06_21_2/logout.php">Log out</a>
    </nav>

    <!-- Product catalog display -->
    <div class="products">
        <?php foreach ($products as $p): ?>
        <div class="product">
            <h3><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="price">$<?= number_format($p['price'], 2) ?></p>
            <form method="post" action="index.php" class="inline">
                <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                <button type="submit" name="action" value="add">Add to Cart</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Cart display -->
    <h2 id="cart">Your Cart</h2>
    <?php if (empty($_SESSION['cart'])): ?>
    <p class="info center">Your cart is empty.</p>
    <?php else: ?>
    <table>
        <tr>
            <th>Product</th>
            <th class="money">Price</th>
            <th>Qty</th>
            <th class="money">Subtotal</th>
            <th></th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $pid => $qty): ?>
        <?php if (isset($products[$pid])): ?>
        <tr>
            <td><?= htmlspecialchars($products[$pid]['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="money">$<?= number_format($products[$pid]['price'], 2) ?></td>
            <td><?= (int)$qty ?></td>
            <td class="money">$<?= number_format($products[$pid]['price'] * $qty, 2) ?></td>
            <td>
                <form method="post" action="index.php" class="inline">
                    <input type="hidden" name="product_id" value="<?= (int)$pid ?>">
                    <button type="submit" name="action" value="remove" class="small">Remove one</button>
                </form>
            </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td colspan="3">Total</td>
            <td class="money">$<?= number_format($total, 2) ?></td>
            <td></td>
        </tr>
    </table>
    <p class="center"><a href="cart_clear.php">Clear Cart</a></p>
    <?php endif; ?>
<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
