<?php
declare(strict_types=1);

/*
 * index.php - CS2623 SSP08 Exercise 16-1 (Ch16) - Author: Fraire, Fidel
 * File I/O demo: appends form entries to data/log.txt with
 * file_put_contents(FILE_APPEND | LOCK_EX), reads it back with
 * file_get_contents() behind a file_exists() guard, lists the data folder
 * with scandir() + is_file(), clears the log, and downloads a file through a
 * Content-Disposition header. Any user-supplied filename goes through basename().
 */

$dataDir = __DIR__ . '/data/';
$logName = 'log.txt';
$logFile = $dataDir . $logName;
$tz      = new DateTimeZone('America/Chicago');   // Apollo runs UTC

$message     = '';
$isError     = false;
$fileContent = '';

// Create data directory if it doesn't exist
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// ── Download (GET ?download=<filename>) ─────────────────────────
// Must run before any HTML is sent, because header() fails after output.
$requested = filter_input(INPUT_GET, 'download');
if (is_string($requested) && $requested !== '') {
    // basename() strips any folder part, so "../../SSP01/index.php" becomes
    // "index.php" and can only ever point inside data/
    $safeName = basename($requested);
    $path     = $dataDir . $safeName;

    // Allowlist of extensions that may be downloaded (never a blocklist).
    // Only files that really exist in data/, and no hidden dotfiles.
    $allowed = ['txt'];
    $ext     = strtolower(pathinfo($safeName, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed, true) && !str_starts_with($safeName, '.') && is_file($path)) {
        header('Content-Type: text/plain; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $safeName . '"');
        header('Content-Length: ' . filesize($path));
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    $message = 'That file cannot be downloaded.';
    $isError = true;
}

// ── Handle form POST ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = filter_input(INPUT_POST, 'action') ?? 'save';

    if ($action === 'clear') {
        // Reset the log to an empty file
        file_put_contents($logFile, '', LOCK_EX);
        chmod($logFile, 0644);
        header('Location: index.php?status=cleared');
        exit;
    }

    // The entry is read raw and escaped only on output. Saving it with
    // FILTER_SANITIZE_SPECIAL_CHARS would store "&#39;" in the file and then
    // htmlspecialchars() would show it as literal text on the page.
    $entry = filter_input(INPUT_POST, 'entry') ?? '';

    // Newlines would let one entry fake extra log lines, so collapse them
    $entry = trim(preg_replace('/\s+/u', ' ', $entry) ?? '');
    $entry = mb_substr($entry, 0, 200);

    if ($entry !== '') {
        // Apollo date fix: DateTime + America/Chicago instead of date()
        $now  = new DateTime('now', $tz);
        $line = $now->format('Y-m-d H:i:s T') . ' — ' . $entry . PHP_EOL;
        file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
        chmod($logFile, 0644);   // owner read/write, everyone else read only

        // Redirect after POST so a page refresh doesn't save the entry twice
        header('Location: index.php?status=saved');
        exit;
    }

    $message = 'Entry cannot be empty.';
    $isError = true;
}

// Message after a redirect
$status = filter_input(INPUT_GET, 'status') ?? '';
if ($status === 'saved') {
    $message = 'Entry saved.';
} elseif ($status === 'cleared') {
    $message = 'Log cleared.';
}

// ── Read log file (guarded) ─────────────────────────────────────
$fileSize = 0;
if (file_exists($logFile)) {
    $fileContent = file_get_contents($logFile);
    if ($fileContent === false) {
        $fileContent = '';
    }
    clearstatcache(true, $logFile);   // filesize() is cached; get the fresh value
    $fileSize = filesize($logFile);
}

// ── List files in data directory ────────────────────────────────
// is_file() drops "." and ".." (and any sub-folders); dotfiles like
// .gitkeep are hidden too
$files = array_filter(
    scandir($dataDir),
    fn($f) => is_file($dataDir . $f) && !str_starts_with($f, '.')
);

$page_title = 'CS2623 SSP08 Ex 16-1: Fraire';
$hub        = 'SSP08';
require_once __DIR__ . '/../../PHP/include_header.php';
?>
    <h2>SSP08 Exercise 16-1 — File Log Demo</h2>

    <?php if ($message !== ''): ?>
    <p class="<?= $isError ? 'error' : 'success' ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="index.php">
        <fieldset>
            <legend>Add to the log</legend>
            <div class="form-row">
                <label for="entry">New log entry:</label>
                <input type="text" id="entry" name="entry" maxlength="200" required>
            </div>
            <button type="submit" name="action" value="save">Save Entry</button>
        </fieldset>
    </form>

    <h3>Log Contents</h3>
    <?php if ($fileContent !== ''): ?>
    <pre class="log"><?= htmlspecialchars($fileContent, ENT_QUOTES, 'UTF-8') ?></pre>
    <?php else: ?>
    <p>No entries yet.</p>
    <?php endif; ?>

    <p>
        File size: <strong><?= htmlspecialchars(number_format($fileSize), ENT_QUOTES, 'UTF-8') ?> bytes</strong>
        <?php if (file_exists($logFile)): ?>
        | <a href="index.php?download=<?= htmlspecialchars(urlencode($logName), ENT_QUOTES, 'UTF-8') ?>">Download Log</a>
        <?php endif; ?>
    </p>

    <!-- Separate form so "Clear Log" never posts the entry field -->
    <form method="post" action="index.php"
          onsubmit="return confirm('Delete every entry in the log?');">
        <button type="submit" name="action" value="clear">Clear Log</button>
    </form>

    <h3>Files in /data/</h3>
    <?php if (count($files) > 0): ?>
    <ul>
    <?php foreach ($files as $f): ?>
        <li><?= htmlspecialchars(basename($f), ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p>The data folder is empty.</p>
    <?php endif; ?>

<?php require_once __DIR__ . '/../../PHP/include_footer.php'; ?>
