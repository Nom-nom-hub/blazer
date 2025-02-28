<?php

/**
 * Define your application routes
 * 
 * Examples:
 * $router->get('/', 'HomeController@index');
 * $router->post('/users', 'UserController@store');
 */

/** @var \Blazer\Core\Router $router */

// Welcome page route (default for new installations)
$router->get('/', 'WelcomeController@index');

// Home routes
$router->get('/home', 'HomeController@index');
$router->get('/about', 'WelcomeController@about');

// User routes with named parameters
$router->get('/users', 'UserController@index');
$router->get('/users/{id}', 'UserController@show');
$router->post('/users', 'UserController@store');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@destroy');

// Add your routes below this line
// $router->get('/example', 'ExampleController@index'); 

// Cache compiled routes
if (PHP_SAPI !== 'cli') {
    // Clear existing route cache
    $cache = \Blazer\Core\Cache::getInstance();
    $cache->set('routes.compiled', null);  // Clear the cache
    
    $routeCache = $cache->get('routes.compiled');
    if ($routeCache) {
        $router->setRoutes($routeCache);
    } else {
        $compiledRoutes = $router->compile();
        $cache->set('routes.compiled', $compiledRoutes, 1440);
    }
} 

// You can add more routes here
$router->get('/contact', 'WelcomeController@contact'); 