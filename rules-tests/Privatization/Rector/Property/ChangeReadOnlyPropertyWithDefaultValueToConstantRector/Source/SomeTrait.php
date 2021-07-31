<?php

declare(strict_types=1);

namespace Rector\Tests\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector\Source;

class SomeTrait
{
    public function process(array $value)
    {
        $this->magicMethods = $value;
    }
}
