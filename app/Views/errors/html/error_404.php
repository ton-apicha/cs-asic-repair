<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f4f4f4; }
        .container { text-align: center; }
        h1 { font-size: 120px; color: #e74c3c; margin: 0; line-height: 1; }
        h2 { color: #333; margin: 20px 0 10px; }
        p { color: #666; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p><?= esc($message ?? 'The page you are looking for could not be found.') ?></p>
        <p><a href="/">Go to Home</a></p>
    </div>
</body>
</html>

