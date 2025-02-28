<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
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
        .user-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            margin-right: 10px;
        }
        .button:hover {
            background-color: #2980b9;
            text-decoration: none;
        }
        .button.delete {
            background-color: #e74c3c;
        }
        .button.delete:hover {
            background-color: #c0392b;
        }
        .actions {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Details</h1>
        
        <div class="user-details">
            <div class="detail-row">
                <span class="label">ID:</span>
                <span><?= $user->id ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Name:</span>
                <span><?= $user->name ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Email:</span>
                <span><?= $user->email ?></span>
            </div>
            <?php if (isset($user->created_at)): ?>
                <div class="detail-row">
                    <span class="label">Created:</span>
                    <span><?= $user->created_at ?></span>
                </div>
            <?php endif; ?>
            <?php if (isset($user->updated_at)): ?>
                <div class="detail-row">
                    <span class="label">Updated:</span>
                    <span><?= $user->updated_at ?></span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="actions">
            <a href="/users" class="button">Back to List</a>
            <button onclick="deleteUser(<?= $user->id ?>)" class="button delete">Delete User</button>
        </div>
    </div>

    <script>
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    window.location.href = '/users';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html> 