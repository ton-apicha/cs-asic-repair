<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 40px; background: #f4f4f4; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #e74c3c; margin-top: 0; }
        pre { background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .message { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= esc($title ?? 'Error') ?></h1>
        <div class="message">
            <strong><?= esc($exception->getMessage()) ?></strong>
        </div>
        <?php if (ENVIRONMENT !== 'production'): ?>
        <p><strong>File:</strong> <?= esc($exception->getFile()) ?></p>
        <p><strong>Line:</strong> <?= esc($exception->getLine()) ?></p>
        <h3>Trace:</h3>
        <pre><?= esc($exception->getTraceAsString()) ?></pre>
        <?php endif; ?>
    </div>
</body>
</html>

