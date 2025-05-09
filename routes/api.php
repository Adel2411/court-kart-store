<?php

/**
 * Define API routes
 *
 * This file registers all API routes for the application
 */

// API Routes
$router->get('/api/products/(\d+)', 'ApiController@getProduct');
$router->get('/api/orders/(\d+)', 'ApiController@getOrder');

// Add more API routes here as your application grows
