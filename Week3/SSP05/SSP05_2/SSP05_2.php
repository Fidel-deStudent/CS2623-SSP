<?php
declare(strict_types=1);

/*
 * SSP05_2.php - CS2623 SSP05 Exercise 7-2 (Ch07)
 * Self-processing product order form with a checkbox array (options[]).
 * Validates the order and shows a summary with the total once every field passes.
 */

// filter_input(INPUT_SERVER) returns NULL on some servers (e.g. php -S),
// so fall back to $_SERVER before defaulting to GET
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD') ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Products and their prices
$products = [
    'Wireless Mouse'      => 24.99,
    'Mechanical Keyboard' => 89.99,
    'USB-C Hub'           => 39.50,
    '27" Monitor'         => 249.00,
];

$option_list = ['Gift Wrap', 'Express Shipping', 'Insurance'];

$payment_list = ['credit' => 'Credit Card', 'debit' => 'Debit Card', 'paypal' => 'PayPal'];

// Defaults for the first (GET) load
$cust_name    = '';
$product      = '';
$quantity     = 1;
$payment      = 'none';
$instructions = '';
$options      = [];
$errors       = [];

if ($method === 'POST') {
    $cust_name    = trim(filter_input(INPUT_POST, 'cust_name') ?? '');
    $product      = filter_input(INPUT_POST, 'product')   ?? '';
    $quantity     = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]) ?? 1;
    $payment      = filter_input(INPUT_POST, 'payment')   ?? 'none';
    $instructions = filter_input(INPUT_POST, 'instructions') ?? '';

    // checkbox array: options[] with FILTER_REQUIRE_ARRAY
    $options = filter_input(INPUT_POST, 'options',
        FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) ?? [];

    if ($cust_name === '') {
        $errors[] = 'Customer name is required.';
    }
    if (!array_key_exists($product, $products)) {
        $errors[] = 'Please choose a product.';
    }
    if ($quantity === false) {
        $errors[] = 'Quantity must be a whole number of 1 or more.';
    }
    if ($payment === 'none') {
        $errors[] = 'Please choose a payment method.';
    }
}

// The order summary is only shown once every field passes validation
$show_summary = $method === 'POST' && empty($errors);

$total = 0.0;
if ($show_summary) {
    $total = $products[$product] * $quantity;
}

// Shared header and footer (same look as the Week 1 and Week 2 pages)
$page_title = 'CS2623 SSP05 Ex 7-2: Fraire';
require_once __DIR__ . '/../PHP/include_header.php';
?>
    <?php require_once __DIR__ . '/../PHP/include_exerciseNav.php'; ?>
    <h2><?php echo htmlspecialchars('SSP05 Exercise 7-2 — Product Order Form', ENT_QUOTES, 'UTF-8'); ?></h2>

    <?php foreach ($errors as $error): ?>
    <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endforeach; ?>

    <form action="SSP05_2.php" method="post">
        <fieldset>
            <legend>Order Details</legend>

            <div class="form-row">
                <label for="cust_name">Customer Name:</label>
                <input type="text" id="cust_name" name="cust_name" required
                       value="<?php echo htmlspecialchars($cust_name, ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-row">
                <label for="product">Product:</label>
                <select id="product" name="product">
                    <?php foreach ($products as $name => $price): ?>
                    <option value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo $product === $name ? 'selected' : ''; ?>><?php echo htmlspecialchars($name . ' – $' . number_format($price, 2), ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity"
                       value="<?php echo htmlspecialchars($quantity === false ? '' : (string)$quantity, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Options &amp; Payment</legend>

            <div class="form-row">
                <span class="field-name">Options/Add-ons:</span>
                <div class="choices">
                    <?php foreach ($option_list as $opt): ?>
                    <label>
                        <input type="checkbox" name="options[]"
                               value="<?php echo htmlspecialchars($opt, ENT_QUOTES, 'UTF-8'); ?>"
                               <?php echo in_array($opt, $options, true) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($opt, ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-row">
                <span class="field-name">Payment Method:</span>
                <div class="choices">
                    <?php foreach ($payment_list as $value => $text): ?>
                    <label>
                        <input type="radio" name="payment"
                               value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                               <?php echo $payment === $value ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-row">
                <label for="instructions">Special Instructions:</label>
                <textarea id="instructions" name="instructions" rows="4"><?php echo htmlspecialchars($instructions, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
        </fieldset>

        <input type="submit" value="Place Order">
    </form>

<?php if ($show_summary): ?>
    <h2><?php echo htmlspecialchars('Order Summary', ENT_QUOTES, 'UTF-8'); ?></h2>
    <table>
        <tr>
            <th>Item</th>
            <th>Details</th>
        </tr>
        <tr>
            <td>Customer</td>
            <td><?php echo htmlspecialchars($cust_name, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Product</td>
            <td><?php echo htmlspecialchars($product, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Unit Price</td>
            <td><?php echo htmlspecialchars('$' . number_format($products[$product], 2), ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Quantity</td>
            <td><?php echo htmlspecialchars((string)$quantity, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Payment</td>
            <td><?php echo htmlspecialchars($payment_list[$payment] ?? $payment, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Selected Options</td>
            <td>
                <ul>
                <?php foreach ($options as $opt): ?>
                    <li><?php echo htmlspecialchars($opt, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
                <?php if (empty($options)): ?>
                    <li>None</li>
                <?php endif; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Instructions</td>
            <td><?php echo nl2br(htmlspecialchars($instructions, ENT_QUOTES, 'UTF-8')); ?></td>
        </tr>
        <tr class="total">
            <td>Order Total</td>
            <td><?php echo htmlspecialchars('$' . number_format($total, 2), ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    </table>
<?php endif; ?>
<?php require_once __DIR__ . '/../PHP/include_footer.php'; ?>
