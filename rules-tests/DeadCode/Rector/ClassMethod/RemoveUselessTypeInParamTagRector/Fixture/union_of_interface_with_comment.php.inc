<?php

declare(strict_types = 1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeInterface;
use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeClass;

class UnionOfInterface {
    /**
     * Some comment
     *
     * @param $someInterface
     */
    public function run(SomeInterface $someInterface)
    {
        if (method_exists($someInterface, 'run')) {
            $someInterface->run();
        }
	}
}
