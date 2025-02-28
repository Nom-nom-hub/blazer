<p align="center">
  <img src="docs/assets/blazer-logo.png" width="200" alt="Blazer Logo">
</p>

<h1 align="center">Blazer Framework</h1>

<p align="center">
  A lightning-fast PHP framework for modern web applications
</p>

<p align="center">
  <a href="https://packagist.org/packages/nomhub/blazer"><img src="https://img.shields.io/packagist/v/nomhub/blazer.svg" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/nomhub/blazer"><img src="https://img.shields.io/packagist/dt/nomhub/blazer.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/nomhub/blazer"><img src="https://img.shields.io/packagist/l/nomhub/blazer.svg" alt="License"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/php-%3E%3D8.0-8892BF.svg" alt="PHP Version"></a>
</p>

## ⚡️ Quick Start

Create a new Blazer project using Composer:

```bash
composer create-project nomhub/blazer my-app
cd my-app
php blazer serve
```

Visit `http://localhost:8000` in your browser to see your application!

## 🎯 Features

- **⚡️ Lightning Fast**: Optimized for speed with minimal overhead
- **🔄 Live Reload**: Built-in development server with automatic page refresh
- **🗺️ Elegant Routing**: Clean and expressive route definitions
- **🎮 MVC Architecture**: Well-organized structure following MVC pattern
- **🔌 Database Layer**: Simple PDO-based database abstraction
- **✨ Form Validation**: Built-in validation system
- **🔒 Session Management**: Secure session handling
- **🧩 View Components**: Reusable UI components
- **💾 Caching System**: Multiple cache drivers for performance
- **🛡️ Middleware System**: Flexible request/response pipeline
- **🔧 CLI Tools**: Command line interface for common tasks

## 📚 Documentation

### Routing

```php
// config/routes.php
$router->get('/', 'HomeController@index');
$router->get('/users/{id}', 'UserController@show');
$router->post('/api/users', 'Api\UserController@store');
```

### Controllers

```php
namespace App\Controllers;

use Blazer\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view('welcome', [
            'title' => 'Welcome to Blazer'
        ]);
    }
}
```

### Models

```php
namespace App\Models;

use Blazer\Core\Model;

class User extends Model
{
    protected static $table = 'users';
    
    public static function findActive()
    {
        return static::where('status', 'active')->get();
    }
}
```

### Database

```php
// Using the DB class
use Blazer\Core\Database\DB;

// Run a query
$users = DB::query("SELECT * FROM users WHERE active = ?", [true]);

// Using models
$activeUsers = User::where('active', true)->get();
$user = User::find(1);
```

### Views

```php
// In your controller
return $this->view('users/profile', [
    'user' => $user,
    'title' => 'User Profile'
]);

// app/Views/users/profile.php
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
    <h1>Welcome, <?= $user->name ?>!</h1>
</body>
</html>
```

## 🛠️ Development

### Directory Structure
```
my-app/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── config/
│   ├── config.php
│   └── routes.php
├── public/
│   └── index.php
├── src/
│   └── Core/
├── storage/
│   ├── cache/
│   └── logs/
└── vendor/
```

### Configuration

Copy `.env.example` to `.env` and update your settings:

```env
APP_NAME=Blazer
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=blazer
DB_USERNAME=root
DB_PASSWORD=
```

## 🤝 Contributing

We welcome contributions! Please feel free to submit a Pull Request.

## 📝 License

The Blazer Framework is open-sourced software licensed under the [MIT license](LICENSE.md).

## 💖 Support

If you find Blazer helpful, please consider:
- Giving it a ⭐️ on GitHub
- Sharing it with friends and colleagues
- [Contributing](#contributing) to the project

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/Nom-nom-hub">Teck</a>
</p>




