<?php

// Router file for Court Kart Store

// Store routes and their handlers
$routes = [];

// Function to register a route
function register_route($route, $callback)
{
    global $routes;
    $routes[$route] = $callback;
}

// Function to handle the current request
function handle_request()
{
    global $routes;

    // Get the page from URL
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';

    // Map page parameter to route path
    $route = ($page === 'home') ? '/' : '/'.$page;

    // Handle the route or show 404
    if (isset($routes[$route])) {
        call_user_func($routes[$route]);
    } else {
        echo '<h2>404 - Page Not Found</h2>';
    }
}
