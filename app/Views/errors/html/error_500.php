<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Internal Server Error</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        h1 {
            color: #2d3748;
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .error-code {
            color: #e53e3e;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        p {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 87, 108, 0.4);
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">🔧</div>
        <div class="error-code">500</div>
        <h1>Internal Server Error</h1>
        <p>
            <?= esc($message ?? 'The server encountered an unexpected condition that prevented it from fulfilling the request.') ?>
        </p>
        <a href="/" class="btn-home">Return to Home</a>
    </div>
</body>

</html>