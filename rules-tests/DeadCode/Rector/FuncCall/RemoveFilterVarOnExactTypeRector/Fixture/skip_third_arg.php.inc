<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector\Fixture;

final class SkipValueToBeModified
{
    public function run(int $value)
    {
        $options = [
            'options' => [
                'min_range' => 10,
            ],
            'flags' => FILTER_FLAG_ALLOW_OCTAL,
        ];

        return filter_var($value, FILTER_VALIDATE_INT, $options);
    }
}
