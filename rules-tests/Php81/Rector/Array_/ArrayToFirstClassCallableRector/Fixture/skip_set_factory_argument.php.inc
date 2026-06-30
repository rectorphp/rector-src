<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Symfony\Component\DependencyInjection\Definition;

final class SkipSetFactoryArgument
{
    public function run(Definition $definition)
    {
        $definition->setFactory([$this, 'name']);
    }

    public function name()
    {
    }
}
