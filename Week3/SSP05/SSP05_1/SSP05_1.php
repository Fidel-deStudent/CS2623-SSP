<?php
declare(strict_types=1);

/*
 * SSP05_1.php - CS2623 SSP05 Exercise 7-1 (Ch07)
 * Self-processing personal info form: shows the form on GET, then
 * validates the POST data with filter_input() and displays it below the form.
 */

// filter_input(INPUT_SERVER) returns NULL on some servers (e.g. php -S),
// so fall back to $_SERVER before defaulting to GET
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD') ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Defaults for the first (GET) load so the form fields start empty
$first    = '';
$last     = '';
$email    = '';
$age      = 0;
$gender   = 'not specified';
$country  = '';
$comments = '';
$errors   = [];

$genders   = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
$countries = ['United States', 'Canada', 'Mexico', 'United Kingdom', 'Germany', 'Japan'];

if ($method === 'POST') {
    // Read and validate form data
    $first    = trim(filter_input(INPUT_POST, 'first_name') ?? '');
    $last     = trim(filter_input(INPUT_POST, 'last_name')  ?? '');
    $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '';
    $age      = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT) ?? 0;
    $gender   = filter_input(INPUT_POST, 'gender')  ?? 'not specified';
    $country  = filter_input(INPUT_POST, 'country') ?? '';
    $comments = filter_input(INPUT_POST, 'comments') ?? '';

    if ($first === '') {
        $errors[] = 'First name is required.';
    }
    if ($last === '') {
        $errors[] = 'Last name is required.';
    }
    if ($email === false || $email === '') {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($age === false) {
        $errors[] = 'Age must be a whole number.';
    }
}

// Shared header and footer (same look as the Week 1 and Week 2 pages)
$page_title = 'CS2623 SSP05 Ex 7-1: Fraire';
require_once __DIR__ . '/../PHP/include_header.php';
?>
    <?php require_once __DIR__ . '/../PHP/include_exerciseNav.php'; ?>
    <h2><?php echo htmlspecialchars('SSP05 Exercise 7-1 — Personal Info Form', ENT_QUOTES, 'UTF-8'); ?></h2>

    <?php foreach ($errors as $error): ?>
    <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endforeach; ?>

    <!-- HTML form: first name, last name, email, age, gender radio, country select, comments textarea, POST -->
    <form action="SSP05_1.php" method="post">
        <fieldset>
            <legend>Contact Information</legend>

            <div class="form-row">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required
                       value="<?php echo htmlspecialchars($first, ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-row">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required
                       value="<?php echo htmlspecialchars($last, ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-row">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email"
                       value="<?php echo htmlspecialchars((string)$email, ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-row">
                <label for="age">Age:</label>
                <input type="text" id="age" name="age"
                       value="<?php echo $age ? htmlspecialchars((string)$age, ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Details</legend>

            <div class="form-row">
                <span class="field-name">Gender:</span>
                <div class="choices">
                    <?php foreach ($genders as $value => $text): ?>
                    <label>
                        <input type="radio" name="gender"
                               value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                               <?php echo $gender === $value ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-row">
                <label for="country">Country:</label>
                <select id="country" name="country">
                    <?php foreach ($countries as $c): ?>
                    <option value="<?php echo htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo $country === $c ? 'selected' : ''; ?>><?php echo htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label for="comments">Comments:</label>
                <textarea id="comments" name="comments" rows="4"><?php echo htmlspecialchars($comments, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
        </fieldset>

        <input type="submit" value="Submit">
    </form>

<?php if ($method === 'POST'): ?>
    <h2><?php echo htmlspecialchars('Submitted Information', ENT_QUOTES, 'UTF-8'); ?></h2>
    <table>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Name</td>
            <td><?php echo htmlspecialchars($first . ' ' . $last, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo htmlspecialchars($email ?: '(invalid)', ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Age</td>
            <td><?php echo htmlspecialchars($age === false ? '(invalid)' : (string)$age, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Gender</td>
            <td><?php echo htmlspecialchars($gender, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Country</td>
            <td><?php echo htmlspecialchars($country, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td>Comments</td>
            <td><?php echo nl2br(htmlspecialchars($comments, ENT_QUOTES, 'UTF-8')); ?></td>
        </tr>
    </table>
<?php endif; ?>
<?php require_once __DIR__ . '/../PHP/include_footer.php'; ?>
