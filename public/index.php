<?php

// Load bootstrap file
$router = require_once __DIR__.'/../bootstrap.php';

// API Routes
$router->get('/api/products/(\d+)', 'ApiController@getProduct');
$router->get('/api/orders/(\d+)', 'ApiController@getOrder');

// Handle the request
$router->dispatch();
