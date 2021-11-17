<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Router;

$router = new Router();

$router->get("/", "MainController#getHome")
    ->get('/articles', "MainController#getArticles");

// $router->post('/', "");

$router->start();