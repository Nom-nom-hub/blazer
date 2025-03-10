<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #c0392b;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $title ?></h1>
        <div>
            <p><?= $content ?></p>

            <h2>Framework Features</h2>
            <ul>
                <li>MVC Architecture</li>
                <li>Clean Routing System</li>
                <li>Request & Response Handling</li>
                <li>Simple ORM for Database Operations</li>
                <li>View Rendering</li>
            </ul>

            <p><a href="/">Back to Home</a></p>
        </div>
    </div>
</body>
</html> 