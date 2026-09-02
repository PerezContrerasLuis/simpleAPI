<?php
require_once __DIR__ .'/../Controllers/CategoryController.php';

$routes = [
    [
        'method' => 'GET',
        'pattern' => '/^\/api\/v1\/categories$/',
        'controller' => 'CategoryController',
        'action' => 'getAll'
    ],
    [
        'method' => 'GET',
        'pattern' => '/^\/api\/v1\/categories\/(\d+)$/',
        'controller' => 'CategoryController',
        'action' => 'getById'
    ]
];
?>