<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

class SkipDirectReturnFuncCall
{
    public function getValue(): int|false|null
    {
        /** @var int|false|null */
        return filter_input(INPUT_GET, 'value', FILTER_VALIDATE_INT);
    }
}
