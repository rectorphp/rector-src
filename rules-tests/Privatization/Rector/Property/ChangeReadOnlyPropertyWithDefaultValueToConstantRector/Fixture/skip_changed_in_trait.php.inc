<?php

namespace Rector\Tests\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector\Fixture;

use Rector\Tests\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector\Source\SomeTrait;

class SkipChangedInTrait
{
    use SomeTrait;

    /**
     * @var string[]
     */
    private $magicMethods = [
        '__toString',
        '__wakeup',
    ];

    public function run()
    {
        foreach ($this->magicMethods as $magicMethod) {
            echo $magicMethod;
        }
    }
}

?>
