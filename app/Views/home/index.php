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
        <p><?= $description ?></p>
        
        <div>
            <h2>Getting Started</h2>
            <p>
                Blazer provides a clean and organized structure for building PHP applications.
                It follows the MVC pattern to help you organize your code effectively.
            </p>
            
            <h2>Quick Links</h2>
            <ul>
                <li><a href="/about">About Blazer</a></li>
                <li><a href="/users">View Users</a></li>
            </ul>
        </div>
    </div>
</body>
</html> 