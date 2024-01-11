<?php

namespace FastRoute\controllers;

class UserController
{
    public function index()
    {
        echo 'index users';
    }
    public function show(int $id)
    {
        var_dump($id);
    }
}
