<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\Source\AnotherType;

final class SkipOnArrayDimFetch
{
    /**
     * @var AnotherType[]
     */
    private $data = [];

    public function run($key): AnotherType
    {
        if (! empty($this->data[$key])) {
            return $this->data[$key];
        }

        $this->data[$key] = new AnotherType();
        return $this->data[$key];
    }
}
