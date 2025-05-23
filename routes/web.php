<?php

/**
 * Define application routes
 *
 * This file registers all routes for the application
 */

// Public routes (accessible to all)
$router->get('/', 'HomeController@index');
$router->get('/shop', 'ShopController@index');
$router->get('/shop/product/{id}', 'ShopController@show');
$router->get('/shop/category/{id}', 'ShopController@category');
$router->post('/shop/product/{id}/review', 'ShopController@submitReview', 'auth');

// Guest routes (only for non-authenticated users)
$router->get('/login', 'AuthController@loginForm', 'guest');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm', 'guest');
$router->post('/register', 'AuthController@register');

// Wishlist routes
$router->get('/wishlist', 'WishlistController@index', 'auth');
$router->post('/wishlist/add', 'WishlistController@add');
$router->post('/wishlist/remove', 'WishlistController@remove');
$router->get('/wishlist/check', 'WishlistController@check');

// Authenticated user routes
$router->get('/cart', 'CartController@index', 'auth');
$router->post('/cart/add', 'CartController@add', 'auth');
$router->post('/cart/update', 'CartController@update', 'auth');
$router->post('/cart/remove', 'CartController@remove', 'auth');
$router->get('/checkout', 'CheckoutController@index', 'auth');
$router->post('/checkout', 'CheckoutController@process', 'auth');
$router->get('/checkout/success', 'CheckoutController@success', 'auth');
$router->get('/orders', 'OrderController@index', 'auth');
$router->get('/orders/{id}', 'OrderController@show', 'auth');
$router->post('/orders/{id}/cancel', 'OrderController@cancel', 'auth');
$router->get('/account', 'AccountController@index', 'auth');
$router->get('/account/edit', 'AccountController@edit', 'auth');
$router->post('/account/update', 'AccountController@update', 'auth');
$router->get('/logout', 'AuthController@logout', 'auth');

// Admin routes
$router->get('/admin', 'AdminController@index', 'admin');
$router->get('/admin/products', 'AdminController@products', 'admin');
$router->post('/admin/products/save', 'AdminController@saveProduct', 'admin');
$router->post('/admin/products/delete', 'AdminController@deleteProduct', 'admin');
$router->get('/admin/orders', 'AdminController@orders', 'admin');
$router->get('/admin/orders/{id}', 'AdminController@showOrder', 'admin');
$router->post('/admin/orders/update-status', 'AdminController@updateOrderStatus', 'admin');
$router->get('/admin/users', 'AdminController@users', 'admin');
$router->post('/admin/users/update', 'AdminController@updateUser', 'admin');
$router->post('/admin/users/delete', 'AdminController@deleteUser', 'admin');

// API Routes
$router->get('/api/products/{id}', 'ApiController@getProduct');
$router->get('/api/wishlist/check/{id}', 'ApiController@checkWishlist');

// Error handlers
$router->get('/unauthorized', function () {
    require_once BASE_PATH.'/views/errors/unauthorized.php';
});

// Set 404 handler
$router->setNotFoundHandler(function () {
    require_once BASE_PATH.'/views/errors/404.php';
});
