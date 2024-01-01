<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\Fixture;

use Symfony\Component\Routing\Annotation\Route;

final class SomeClassMethod
{
    /**
     * @Route({"en"="/en"}, name="homepage")
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
