<?php

// define routes

$routes = array(
    array(
        "pattern" => "login",
        "controller" => "home",
        "action" => "login"
    ),
    array(
        "pattern" => "register",
        "controller" => "home",
        "action" => "register"
    ),
    array(
        "pattern" => "logout",
        "controller" => "home",
        "action" => "logout"
    ),
    array(
        "pattern" => "sync",
        "controller" => "home",
        "action" => "sync"
    ),
    array(
        "pattern" => "home",
        "controller" => "home",
        "action" => "index"
    )
);

// add defined routes
foreach ($routes as $route) {
    $router->addRoute(new Framework\Router\Route\Simple($route));
}

// unset globals
unset($routes);
