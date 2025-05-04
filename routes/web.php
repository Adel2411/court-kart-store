<?php

/**
 * Define application routes
 * 
 * This file registers all routes for the application
 */

// Home route
$router->get('/', 'ShopController@index');

// Shop routes
$router->get('/shop', 'ShopController@index');
$router->get('/shop/product/{id}', 'ShopController@show');
$router->get('/shop/category/{id}', 'ShopController@category');

// Cart routes
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');

// Auth routes
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Admin routes
$router->get('/admin', 'AdminController@index');
$router->get('/admin/products', 'AdminController@products');
$router->get('/admin/orders', 'AdminController@orders');
$router->get('/admin/users', 'AdminController@users');

// Set 404 handler
$router->setNotFoundHandler(function() {
    require_once BASE_PATH . '/views/errors/404.php';
});
