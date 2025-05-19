<?php

/**
 * Define API routes
 *
 * This file registers all API routes for the application
 */
$router->get('/api/products/(\d+)', 'ApiController@getProduct');
$router->get('/api/orders/(\d+)', 'ApiController@getOrder');
$router->get('/api/wishlist/check/(\d+)', 'ApiController@checkWishlistItem');
