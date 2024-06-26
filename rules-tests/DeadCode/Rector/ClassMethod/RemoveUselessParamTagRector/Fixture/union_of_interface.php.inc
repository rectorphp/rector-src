<?php

declare(strict_types = 1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source\SomeInterface;
use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source\SomeClass;

class UnionOfInterface {
    /**
     * @param SomeClass|SomeInterface $someInterface
     */
    public function run(SomeInterface $someInterface)
    {
        if (method_exists($someInterface, 'run')) {
            $someInterface->run();
        }
	}
}

?>
-----
<?php

declare(strict_types = 1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source\SomeInterface;
use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source\SomeClass;

class UnionOfInterface {
    public function run(SomeInterface $someInterface)
    {
        if (method_exists($someInterface, 'run')) {
            $someInterface->run();
        }
	}
}

?>
