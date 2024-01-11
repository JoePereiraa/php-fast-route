<?php

use FastRoute\core\Router;
use FastRoute\controllers\HomeController;
use FastRoute\controllers\UserController;
use FastRoute\controllers\AdminPostsController;
use FastRoute\controllers\AdminUsersController;



$router = new Router;

$router->add('GET', '/', [HomeController::class, 'index']);
$router->add('GET', '/users', [UserController::class, 'index']);
$router->add('GET', '/user/{id:[0-9]+}', [UserController::class, 'show']);

#Routes Admin
$admin = require 'admin.php';
$router->group('/admin', $admin);

$router->run();
