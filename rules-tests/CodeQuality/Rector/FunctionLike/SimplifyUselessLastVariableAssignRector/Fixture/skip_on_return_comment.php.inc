<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipOnReturnComment
{
    public function run()
    {
        $name = $this->getValue();

        /** @var string $name */
        return $name;
    }

    private function getValue()
    {
        return 'name';
    }
}
