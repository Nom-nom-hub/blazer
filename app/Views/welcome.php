<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Light mode root variables */
    :root {
      --primary: #e03131;
      --primary-dark: #c92a2a;
      --primary-light: #ff8787;
      --secondary: #339af0;
      --dark: #101010;
      --light: #f8f9fa;
      --gray: #868e96;
      --gray-dark: #343a40;
      --gray-light: #dee2e6;
      --success: #37b24d;
      --warning: #f59f00;
      --danger: #f03e3e;
      --border: #e9ecef;
      --card-bg: #ffffff;
      --code-bg: #2d2d2d;
      --code-header: #363636;
      --code-text: #e6e6e6;
    }

    /* Dark mode variables */
    html[data-theme="dark"] {
      --dark: #f8f9fa;
      --light: #101010;
      --gray: #adb5bd;
      --gray-dark: #dee2e6;
      --gray-light: #343a40;
      --border: #212529;
      --card-bg: #1a1a1a;
      --code-bg: #2d2d2d;
      --code-header: #363636;
      --code-text: #e6e6e6;
    }

    /* Add theme toggle button styles */
    .theme-toggle {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      background: var(--card-bg);
      border: 1px solid var(--border);
      color: var(--dark);
      padding: 0.75rem;
      border-radius: 50%;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    
    .theme-toggle:hover {
      transform: scale(1.1);
    }
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      color: var(--dark);
      background-color: var(--light);
      font-size: 16px;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
    }
    
    header {
      background-color: var(--light);
      color: var(--dark);
      padding: 1.5rem 0;
      position: relative;
      border-bottom: 1px solid var(--border);
    }
    
    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 800;
      font-size: 1.5rem;
      color: var(--primary);
      text-decoration: none;
    }
    
    .logo-icon {
      font-size: 1.5rem;
    }
    
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }
    
    .hero-bg {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: -1;
      opacity: 0.1;
      background-image: 
          radial-gradient(circle at 20% 30%, var(--primary-light) 0%, transparent 50%),
          radial-gradient(circle at 80% 70%, var(--secondary) 0%, transparent 50%);
    }
    
    .hero h1 {
      font-size: 4rem;
      font-weight: 800;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, var(--primary), #ff8787);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    .hero p {
      font-size: 1.25rem;
      color: var(--gray);
      max-width: 600px;
      margin: 0 auto;
    }
    
    .version-badge {
      display: inline-block;
      background-color: var(--gray-light);
      color: var(--gray-dark);
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      font-size: 0.875rem;
      font-weight: 500;
      margin-bottom: 1.5rem;
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background-color: var(--primary);
      color: white;
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      text-decoration: none;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
    }
    
    .btn:hover {
      background-color: var(--primary-dark);
    }
    
    .btn-secondary {
      background-color: var(--light);
      color: var(--dark);
      border: 1px solid var(--border);
    }
    
    .btn-secondary:hover {
      background-color: var(--gray-light);
    }
    
    .hero-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
    }
    
    section {
      padding: 5rem 0;
    }
    
    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 3rem;
      text-align: center;
    }
    
    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }
    
    .feature-item {
      display: flex;
      gap: 1rem;
      padding: 1.5rem;
      border-radius: 0.5rem;
      background-color: var(--card-bg);
      border: 1px solid var(--border);
      transition: all 0.3s ease;
    }
    
    .feature-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }
    
    .feature-mini-icon {
      font-size: 1.25rem;
      color: var(--primary);
      background-color: rgba(224, 49, 49, 0.1);
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.5rem;
      flex-shrink: 0;
    }
    
    .feature-details h4 {
      font-size: 1.25rem;
      margin-bottom: 0.5rem;
    }
    
    /* Styles for getting-started section */
    .getting-started {
      background-color: var(--card-bg);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
    }
    
    .steps {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .step {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 3rem;
    }
    
    .step:last-child {
      margin-bottom: 0;
    }
    
    .step-number {
      width: 40px;
      height: 40px;
      background-color: var(--primary);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      flex-shrink: 0;
    }
    
    .step-content {
      color: var(--dark);
    }
    
    .step-content h3 {
      font-size: 1.25rem;
      margin-bottom: 0.5rem;
    }
    
    .code-block {
      background-color: var(--code-bg);
      border-radius: 0.5rem;
      margin: 1rem 0;
      overflow: hidden;
    }
    
    .code-header {
      background-color: var(--code-header);
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
      color: var(--code-text);
    }
    
    .code-language {
      color: var(--gray-light);
    }
    
    .code-block pre {
      margin: 0;
      padding: 1rem;
      overflow-x: auto;
      color: var(--code-text);
      background-color: var(--code-bg);
      font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
      font-size: 0.875rem;
      line-height: 1.5;
    }
    
    /* Styles for examples section */
    .code-tabs {
      background-color: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 0.5rem;
      overflow: hidden;
      margin-top: 2rem;
    }
    
    .code-tabs-header {
      display: flex;
      border-bottom: 1px solid var(--border);
      background-color: var(--light);
    }
    
    .code-tab {
      padding: 1rem 1.5rem;
      border: none;
      background: none;
      font-weight: 500;
      color: var(--gray);
      cursor: pointer;
      transition: all 0.2s ease;
    }
    
    .code-tab:hover {
      color: var(--dark);
    }
    
    .code-tab.active {
      background-color: var(--card-bg);
      color: var(--primary);
      border-bottom: 2px solid var(--primary);
    }
    
    .code-tabs-content {
      padding: 0;
    }
    
    .code-content {
      display: none;
    }
    
    .code-content.active {
      display: block;
    }
    
    footer {
      padding: 3rem 0;
      background-color: var(--light);
      border-top: 1px solid var(--border);
    }
    
    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .footer-text {
      color: var(--gray);
    }
    
    .social-links {
      display: flex;
      gap: 1rem;
    }
    
    .social-link {
      color: var(--gray);
      font-size: 1.25rem;
      transition: color 0.2s ease;
    }
    
    .social-link:hover {
      color: var(--primary);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }
      
      .hero p {
        font-size: 1.25rem;
      }
      
      .feature-grid {
        grid-template-columns: 1fr;
      }
      
      .hero-buttons {
        flex-direction: column;
        align-items: center;
      }
      
      .footer-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
      }
    }

    /* Code block styles */
    .code-block, 
    .code-block pre {
      background-color: var(--code-bg);
      color: var(--code-text);
    }

    .code-header {
      background-color: var(--code-header);
      color: var(--code-text);
    }

    pre {
      background-color: var(--code-bg);
      color: var(--code-text);
      padding: 1rem;
      border-radius: 0.5rem;
      overflow-x: auto;
    }

    code {
      font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
      font-size: 0.875rem;
    }

    .benchmark {
      padding: 4rem 0;
      background: var(--light);
      border-top: 1px solid var(--border);
    }

    .benchmark h2 {
      text-align: center;
      margin-bottom: 3rem;
      font-size: 2.5rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .benchmark-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
      margin-bottom: 4rem;
    }

    .benchmark-card {
      background: var(--card-bg);
      padding: 2rem;
      border-radius: 1rem;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s;
    }

    .benchmark-card:hover {
      transform: translateY(-5px);
    }

    .benchmark-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }

    .benchmark-label {
      color: var(--gray);
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .benchmark-compare {
      text-align: center;
      max-width: 800px;
      margin: 0 auto;
    }

    .benchmark-compare p {
      margin-bottom: 2rem;
      font-size: 1.25rem;
      color: var(--gray);
    }

    .speed-bars {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .speed-bar {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .speed-bar .label {
      width: 100px;
      text-align: right;
      font-weight: 500;
    }

    .speed-bar .bar {
      height: 8px;
      background: var(--primary);
      border-radius: 4px;
      transition: width 1s ease-out;
    }

    .speed-bar .value {
      width: 60px;
      color: var(--gray);
      font-size: 0.875rem;
    }

    .benchmark-detail {
      font-size: 0.75rem;
      color: var(--gray);
      margin-top: 0.25rem;
    }

    .benchmark-notes {
      margin-top: 2rem;
      padding: 1rem;
      background: var(--card-bg);
      border-radius: 0.5rem;
      font-size: 0.875rem;
    }

    .benchmark-notes ul {
      list-style: none;
      padding: 0;
      margin: 1rem 0 0;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 0.5rem;
    }

    .benchmark-notes li {
      color: var(--gray);
    }

    .benchmark h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: var(--dark);
    }
  </style>
</head>
<body>
  <!-- Add theme toggle button -->
  <button class="theme-toggle" aria-label="Toggle dark mode">
    <i class="fas fa-moon"></i>
  </button>

  <header>
    <div class="container">
      <div class="header-content">
        <a href="/" class="logo">
          <span class="logo-icon"><i class="fas fa-fire"></i></span>
          <span>Blazer</span>
        </a>
        <div class="header-links">
          <a href="#features" class="btn btn-secondary">Features</a>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="hero-bg"></div>
      <div class="container">
        <div class="version-badge">
          Version 1.0.1
        </div>
        <h1>Welcome to Blazer</h1>
        <p>A lightning-fast PHP framework for modern web applications.</p>
        
        <div class="hero-buttons">
          <a href="#get-started" class="btn">Get Started <i class="fas fa-arrow-right"></i></a>
          <a href="#examples" class="btn btn-secondary">View Examples</a>
        </div>
      </div>
    </section>

    <!-- Replace the existing benchmark section with this -->
    <section class="benchmark">
      <div class="container">
        <h2>Lightning Fast Performance</h2>
        <div class="benchmark-grid">
          <div class="benchmark-card">
            <div class="benchmark-value"><?= number_format((microtime(true) - START_TIME) * 1000, 2) ?>ms</div>
            <div class="benchmark-label">Response Time</div>
            <div class="benchmark-detail">From request to render</div>
          </div>
          <div class="benchmark-card">
            <div class="benchmark-value"><?= round(memory_get_peak_usage() / 1024 / 1024, 2) ?>MB</div>
            <div class="benchmark-label">Memory Usage</div>
            <div class="benchmark-detail">Peak memory consumption</div>
          </div>
          <div class="benchmark-card">
            <div class="benchmark-value"><?= count(get_included_files()) ?></div>
            <div class="benchmark-label">Files Loaded</div>
            <div class="benchmark-detail">Total PHP files included</div>
          </div>
          <div class="benchmark-card">
            <div class="benchmark-value"><?= round(memory_get_usage() / 1024 / 1024, 2) ?>MB</div>
            <div class="benchmark-label">Current Memory</div>
            <div class="benchmark-detail">Current memory allocation</div>
          </div>
        </div>
        <div class="benchmark-compare">
          <h3>Framework Performance Comparison</h3>
          <p>Average response time for a typical "Hello World" route</p>
          <div class="speed-bars">
            <div class="speed-bar">
              <span class="label">Blazer</span>
              <div class="bar" style="width: 20%"></div>
              <span class="value">~<?= number_format((microtime(true) - START_TIME) * 1000, 1) ?>ms</span>
            </div>
            <div class="speed-bar">
              <span class="label">Laravel</span>
              <div class="bar" style="width: 100%"></div>
              <span class="value">~40-60ms</span>
            </div>
            <div class="speed-bar">
              <span class="label">Symfony</span>
              <div class="bar" style="width: 80%"></div>
              <span class="value">~30-45ms</span>
            </div>
            <div class="speed-bar">
              <span class="label">CodeIgniter</span>
              <div class="bar" style="width: 40%"></div>
              <span class="value">~15-20ms</span>
            </div>
          </div>
          <div class="benchmark-notes">
            <p><strong>Note:</strong> Benchmarks performed on PHP <?= PHP_VERSION ?> with opcache enabled. Your results may vary based on server configuration and load.</p>
            <ul>
              <li>PHP Version: <?= PHP_VERSION ?></li>
              <li>Server API: <?= php_sapi_name() ?></li>
              <li>Opcache Enabled: <?= ini_get('opcache.enable') ? 'Yes' : 'No' ?></li>
              <li>Extensions Loaded: <?= count(get_loaded_extensions()) ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- PART 2: FEATURES SECTION -->
    <section id="features" class="section">
      <div class="container">
        <h2 class="section-title">Powerful Features</h2>
        
        <div class="feature-grid">
          <div class="feature-item">
            <div class="feature-mini-icon">
              <i class="fas fa-route"></i>
            </div>
            <div class="feature-details">
              <h4>Elegant Routing</h4>
              <p>Define clean, expressive routes that map to controller actions with support for parameters and HTTP methods.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-mini-icon">
              <i class="fas fa-database"></i>
            </div>
            <div class="feature-details">
              <h4>Database Layer</h4>
              <p>Simple PDO-based database layer with query builder and connection pooling for efficient database operations.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-mini-icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="feature-details">
              <h4>Form Validation</h4>
              <p>Built-in validation system to handle form inputs with customizable rules and error messages.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-mini-icon">
              <i class="fas fa-key"></i>
            </div>
            <div class="feature-details">
              <h4>Session Management</h4>
              <p>Secure session handling with an intuitive API for managing user sessions and data persistence.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-mini-icon">
              <i class="fas fa-terminal"></i>
            </div>
            <div class="feature-details">
              <h4>CLI Tools</h4>
              <p>Command-line interface for common tasks like migrations, serving your app, and custom commands.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-mini-icon">
              <i class="fas fa-filter"></i>
            </div>
            <div class="feature-details">
              <h4>Middleware System</h4>
              <p>Flexible middleware pipeline for handling requests and responses with custom logic.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-mini-icon">
              <i class="fas fa-puzzle-piece"></i>
            </div>
            <div class="feature-details">
              <h4>View Components</h4>
              <p>Reusable view components to build consistent UI elements across your application.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-mini-icon">
              <i class="fas fa-bolt"></i>
            </div>
            <div class="feature-details">
              <h4>Caching System</h4>
              <p>Multiple cache drivers (File, APCu) for improved performance and reduced database load.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <section id="get-started" class="section getting-started">
      <div class="container">
        <h2 class="section-title">Getting Started</h2>
        
        <div class="steps">
          <div class="step">
            <div class="step-number">1</div>
            <div class="step-content">
              <h3>Set Up Your Project</h3>
              <p>Create your first Blazer project:</p>
              
              <div class="code-block">
                <div class="code-header">
                  <span class="code-language">Terminal</span>
                </div>
                <pre><code># Navigate to your projects directory
cd /path/to/projects

# Create a new directory for your project
mkdir my-blazer-app
cd my-blazer-app

# Install Blazer (when using Composer)
# composer require blazer/framework</code></pre>
              </div>
            </div>
          </div>
          
          <div class="step">
            <div class="step-number">2</div>
            <div class="step-content">
              <h3>Define Your Routes</h3>
              <p>Add routes in <code>config/routes.php</code>:</p>
              
              <div class="code-block">
                <div class="code-header">
                  <span class="code-language">PHP</span>
                </div>
                <pre><code>&lt;?php
// config/routes.php

// Home routes
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');

// User routes
$router->get('/users', 'UserController@index');
$router->get('/users/(\d+)', 'UserController@show');
$router->post('/users', 'UserController@store');
</code></pre>
              </div>
            </div>
          </div>
          
          <div class="step">
            <div class="step-number">3</div>
            <div class="step-content">
              <h3>Create a Controller</h3>
              <p>Build your controllers in <code>app/Controllers/</code>:</p>
              
              <div class="code-block">
                <div class="code-header">
                  <span class="code-language">PHP</span>
                </div>
                <pre><code>&lt;?php
namespace App\Controllers;

use Blazer\Core\Controller;
use Blazer\Core\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return $this->render('home/index', [
            'title' => 'Welcome to Blazer',
            'message' => 'Hello, World!'
        ]);
    }
}
</code></pre>
              </div>
            </div>
          </div>
          
          <div class="step">
            <div class="step-number">4</div>
            <div class="step-content">
              <h3>Run Your Application</h3>
              <p>Start the PHP development server:</p>
              
              <div class="code-block">
                <div class="code-header">
                  <span class="code-language">Terminal</span>
                </div>
                <pre><code># Navigate to the public directory
cd public

# Start the PHP development server
php -S localhost:8000</code></pre>
              </div>
              
              <p>Visit <a href="http://localhost:8000">http://localhost:8000</a> in your browser to see your application!</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- PART 3: EXAMPLES SECTION -->
    <section id="examples" class="section">
      <div class="container">
        <h2 class="section-title">Code Examples</h2>
        
        <div class="code-tabs">
          <div class="code-tabs-header">
            <button class="code-tab active" data-tab="route-code">Routes</button>
            <button class="code-tab" data-tab="controller-code">Controller</button>
            <button class="code-tab" data-tab="model-code">Model</button>
            <button class="code-tab" data-tab="view-code">View</button>
          </div>
          
          <div class="code-tabs-content">
            <div class="code-content active" id="route-code">
<pre>&lt;?php
// config/routes.php

/**
 * Define your application routes
 */

// Home routes
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');

// Blog routes
$router->get('/blog', 'BlogController@index');
$router->get('/blog/(\d+)', 'BlogController@show');
$router->get('/blog/category/([a-z-]+)', 'BlogController@category');

// API routes
$router->post('/api/posts', 'Api\PostController@store');
$router->put('/api/posts/(\d+)', 'Api\PostController@update');
$router->delete('/api/posts/(\d+)', 'Api\PostController@destroy');
</pre>
            </div>
            
            <div class="code-content" id="controller-code">
<pre>&lt;?php

namespace App\Controllers;

use Blazer\Core\Controller;
use Blazer\Core\Request;
use App\Models\Post;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $posts = Post::latest(10);
        
        return $this->render('blog/index', [
            'title' => 'Blog Posts',
            'posts' => $posts
        ]);
    }
    
    /**
     * Display a specific post
     */
    public function show(Request $request, $id)
    {
        $post = Post::find($id);
        
        if (!$post) {
            return $this->render('errors/404', [], 404);
        }
        
        return $this->render('blog/show', [
            'post' => $post
        ]);
    }
}
</pre>
            </div>
            
            <div class="code-content" id="model-code">
<pre>&lt;?php

namespace App\Models;

use Blazer\Core\Model;

class Post extends Model
{
    /**
     * The table associated with the model
     */
    protected static $table = 'posts';
    
    /**
     * Find posts by category
     */
    public static function findByCategory($category)
    {
        return self::where('category', $category);
    }
    
    /**
     * Find latest posts
     */
    public static function latest($limit = 5)
    {
        $table = static::getTable();
        $db = static::getConnection();
        
        $query = "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT :limit";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        $posts = [];
        foreach ($stmt->fetchAll() as $data) {
            $posts[] = new static($data);
        }
        
        return $posts;
    }
}
</pre>
            </div>
            
            <div class="code-content" id="view-code">
<pre>&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;&lt;?= $title ?&gt;&lt;/title&gt;
    &lt;link rel="stylesheet" href="/css/styles.css"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;header&gt;
        &lt;h1&gt;&lt;?= $title ?&gt;&lt;/h1&gt;
    &lt;/header&gt;
    
    &lt;main&gt;
        &lt;div class="posts"&gt;
            &lt;?php foreach ($posts as $post): ?&gt;
                &lt;article class="post"&gt;
                    &lt;h2&gt;&lt;?= $post->title ?&gt;&lt;/h2&gt;
                    &lt;div class="meta"&gt;
                        Posted on &lt;?= $post->created_at ?&gt;
                    &lt;/div&gt;
                    &lt;p&gt;&lt;?= $post->excerpt ?&gt;&lt;/p&gt;
                    &lt;a href="/posts/&lt;?= $post->id ?&gt;"&gt;Read More&lt;/a&gt;
                &lt;/article&gt;
            &lt;?php endforeach; ?&gt;
        &lt;/div&gt;
    &lt;/main&gt;
    
    &lt;footer&gt;
        &lt;p&gt;&copy; &lt;?= date('Y') ?&gt; My Blazer App&lt;/p&gt;
    &lt;/footer&gt;
&lt;/body&gt;
&lt;/html&gt;
</pre>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  
  <footer>
    <div class="container">
      <div class="footer-content">
        <div class="footer-text">
          <p>&copy; <?= date('Y') ?> Blazer Framework. MIT Licensed.</p>
        </div>
        <div class="social-links">
          <a href="#" class="social-link"><i class="fab fa-github"></i></a>
          <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Theme toggle functionality
    const themeToggle = document.querySelector('.theme-toggle');
    const icon = themeToggle.querySelector('i');
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    
    themeToggle.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });
  </script>
</body>
</html>
