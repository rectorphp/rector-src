<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\Fixture;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hello", name="route_name")
 */
final class RouteWithPrefixAndEmptyName
{
    /**
     * @Route("/", name="")
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
