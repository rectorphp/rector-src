<?php

namespace Rector\Tests\Lumen\Rector\MethodCall\RoutesStringMiddlewareToArrayRector\Fixture;

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group(['middleware' => 'first|second'], function () use ($router) {
    $router->get('/', ['uses' => 'HomeController@home', 'middleware' => 'third|fourth']);
});

?>
-----
<?php

namespace Rector\Tests\Lumen\Rector\MethodCall\RoutesStringMiddlewareToArrayRector\Fixture;

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group(['middleware' => ['first', 'second']], function () use ($router) {
    $router->get('/', ['uses' => 'HomeController@home', 'middleware' => ['third', 'fourth']]);
});

?>
