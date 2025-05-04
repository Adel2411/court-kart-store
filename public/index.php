<?php

// Load bootstrap file
$router = require_once __DIR__ . '/../bootstrap.php';

// Handle the request
$router->dispatch();
