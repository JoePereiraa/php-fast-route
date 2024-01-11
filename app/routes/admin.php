<?php

use FastRoute\controllers\AdminController;
use FastRoute\controllers\AdminPostsController;
use FastRoute\controllers\AdminUsersController;


return function () {
    return [
        ['GET', '', [AdminController::class, 'index']],
        ['GET', '/users', [AdminUsersController::class, 'index']],
        ['GET', '/posts', [AdminPostsController::class, 'index']],
    ];
};
