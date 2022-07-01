<?php

namespace Rector\Tests\Lumen\Rector\MethodCall\RoutesStringMiddlewareToArrayRector\Fixture;

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/', 'HomeController@home');
});

?>
-----
<?php

namespace Rector\Tests\Lumen\Rector\MethodCall\RoutesStringMiddlewareToArrayRector\Fixture;

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group(['middleware' => ['auth']], function () use ($router) {
    $router->get('/', 'HomeController@home');
});

?>
