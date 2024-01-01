<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\Fixture;

use Symfony\Component\Routing\Annotation\Route;
use Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\Source\TestController;

final class RouteName
{
    /**
     * @Route("/hello/", name=TestController::ROUTE_NAME)
     */
    public function run()
    {
    }
}

?>
-----
PhpParser\Node\Stmt\ClassMethod
-----
Symfony\Component\Routing\Annotation\Route
