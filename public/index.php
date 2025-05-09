<?php

// Load bootstrap file
$router = require_once __DIR__.'/../bootstrap.php';

// Load route files
require_once __DIR__.'/../routes/web.php';
require_once __DIR__.'/../routes/api.php';

// Handle the request
$router->dispatch();
