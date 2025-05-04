<?php

/**
 * Define application routes
 *
 * This file registers all routes for the application
 */

// Public routes (accessible to all)
$router->get('/', 'ShopController@index');
$router->get('/shop', 'ShopController@index');
$router->get('/shop/product/{id}', 'ShopController@show');
$router->get('/shop/category/{id}', 'ShopController@category');

// Guest routes (only for non-authenticated users)
$router->get('/login', 'AuthController@loginForm', 'guest');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm', 'guest');
$router->post('/register', 'AuthController@register');

// Authenticated user routes
$router->get('/cart', 'CartController@index', 'auth');
$router->post('/cart/add', 'CartController@add', 'auth');
$router->post('/cart/update', 'CartController@update', 'auth');
$router->post('/cart/remove', 'CartController@remove', 'auth');
$router->get('/checkout', 'CheckoutController@index', 'auth');
$router->get('/account', 'AccountController@index', 'auth');
$router->get('/orders', 'AccountController@orders', 'auth');
$router->get('/logout', 'AuthController@logout', 'auth');

// Admin routes
$router->get('/admin', 'AdminController@index', 'admin');
$router->get('/admin/products', 'AdminController@products', 'admin');
$router->get('/admin/orders', 'AdminController@orders', 'admin');
$router->get('/admin/users', 'AdminController@users', 'admin');

// Error handlers
$router->get('/unauthorized', function () {
    require_once BASE_PATH.'/views/errors/unauthorized.php';
});

// Set 404 handler
$router->setNotFoundHandler(function () {
    require_once BASE_PATH.'/views/errors/404.php';
});
