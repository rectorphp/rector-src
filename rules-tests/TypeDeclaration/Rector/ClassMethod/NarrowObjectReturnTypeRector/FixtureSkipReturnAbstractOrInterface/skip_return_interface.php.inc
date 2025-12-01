<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\FixtureSkipReturnAbstractOrInterface;

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;

final class SkipReturnInterface
{
    public function create(): ContainerInterface
    {
        return new Container();
    }
}

?>