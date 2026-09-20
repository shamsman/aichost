<?php
/**
 * AICHost Live Web Migration Runner
 * 
 * Allows running database migrations safely on production/live server without SSH.
 * Usage: https://yourdomain.com/migrate.php?token=YOUR_MIGRATE_SECRET
 */

declare(strict_types=1);

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap console kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$expectedToken = env('MIGRATE_SECRET', 'aichost_secure_migrate_token_2026');
$providedToken = $_GET['token'] ?? $_POST['token'] ?? ($_SERVER['HTTP_X_MIGRATE_TOKEN'] ?? '');

$wantsJson = (isset($_GET['format']) && $_GET['format'] === 'json') || 
             (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

$authenticated = !empty($expectedToken) && !empty($providedToken) && hash_equals($expectedToken, $providedToken);

$action = $_GET['action'] ?? $_POST['action'] ?? 'status';
$output = '';
$status = 'idle';
$error = null;

if ($authenticated && in_array($action, ['migrate', 'seed', 'status'])) {
    try {
        ob_start();
        if ($action === 'migrate') {
            $kernel->call('migrate', ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            $status = 'success';
        } elseif ($action === 'seed') {
            $kernel->call('db:seed', ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            $status = 'success';
        } elseif ($action === 'status') {
            $kernel->call('migrate:status');
            $output = \Illuminate\Support\Facades\Artisan::output();
            $status = 'info';
        }
        ob_end_clean();
    } catch (\Throwable $e) {
        $status = 'error';
        $error = $e->getMessage();
        $output = $e->getTraceAsString();
    }
}

if ($wantsJson) {
    header('Content-Type: application/json');
    if (!$authenticated) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized: Invalid or missing token']);
        exit;
    }
    echo json_encode([
        'success' => $status !== 'error',
        'action'  => $action,
        'status'  => $status,
        'output'  => $output,
        'error'   => $error,
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AICHost — Live Database Migrations</title>
    <style>
        :root {
            --bg: #06080f;
            --surface: #0d121f;
            --card: #131929;
            --border: rgba(99, 102, 241, 0.2);
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.4);
            --accent: #06b6d4;
            --text: #f8fafc;
            --muted: #94a3b8;
            --success: #10b981;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, monospace; }
        body {
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .container {
            width: 100%;
            max-width: 720px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6), 0 0 30px var(--primary-glow);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding-bottom: 16px;
        }
        .logo-badge {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }
        h1 { font-size: 22px; font-weight: 700; letter-spacing: -0.5px; }
        .subtitle { font-size: 13px; color: var(--muted); }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; color: var(--muted); }
        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus { border-color: var(--primary); }
        .button-group { display: flex; gap: 12px; margin-top: 20px; }
        .btn {
            flex: 1;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            color: white;
            box-shadow: 0 4px 15px var(--primary-glow);
        }
        .btn-primary:hover { opacity: 0.95; transform: translateY(-1px); }
        .btn-secondary {
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.05); }
        .console-output {
            margin-top: 24px;
            background: #000;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 16px;
            font-size: 13px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
            max-height: 350px;
            overflow-y: auto;
            color: #38bdf8;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-success { background: rgba(16, 185, 129, 0.2); color: var(--success); }
        .status-error { background: rgba(239, 68, 68, 0.2); color: var(--danger); }
        .notice {
            margin-top: 20px;
            padding: 12px 16px;
            background: rgba(99, 102, 241, 0.1);
            border-left: 3px solid var(--primary);
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-badge">⚡</div>
            <div>
                <h1>AICHost Database Migrations</h1>
                <div class="subtitle">Secure Live Migration Runner (Zero-SSH Infrastructure)</div>
            </div>
        </div>

        <form method="GET" action="migrate.php">
            <div class="form-group">
                <label for="token">Security Token</label>
                <input type="password" id="token" name="token" value="<?= htmlspecialchars($providedToken) ?>" placeholder="Enter MIGRATE_SECRET" required>
            </div>

            <div class="button-group">
                <button type="submit" name="action" value="migrate" class="btn btn-primary">Run Migrations (--force)</button>
                <button type="submit" name="action" value="seed" class="btn btn-secondary">Seed Database</button>
                <button type="submit" name="action" value="status" class="btn btn-secondary">Check Migration Status</button>
            </div>
        </form>

        <?php if (!empty($providedToken) && !$authenticated): ?>
            <div class="notice" style="border-left-color: var(--danger); background: rgba(239, 68, 68, 0.1); color: var(--danger); margin-top: 20px;">
                <strong>Authentication Failed:</strong> Provided token is invalid. Please verify <code>MIGRATE_SECRET</code> in your environment.
            </div>
        <?php endif; ?>

        <?php if ($status !== 'idle'): ?>
            <div style="margin-top: 24px; display: flex; align-items: center; justify-content: space-between;">
                <strong>Execution Results (Action: <?= htmlspecialchars($action) ?>):</strong>
                <span class="status-badge <?= $status === 'success' || $status === 'info' ? 'status-success' : 'status-error' ?>">
                    <?= htmlspecialchars($status) ?>
                </span>
            </div>
            <div class="console-output"><?= htmlspecialchars($error ? "ERROR: {$error}\n\n{$output}" : ($output ?: 'Command finished with no output.')) ?></div>
        <?php endif; ?>

        <div class="notice">
            <strong>Deployment Note:</strong> In accordance with your deployment workflow, code is pushed to GitHub and deployed to the server automatically. Migrations on the live server are executed by visiting this page or hitting:
            <br><code>https://aichost.com/migrate.php?token=YOUR_MIGRATE_SECRET&action=migrate</code>
        </div>
    </div>
</body>
</html>
