<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class SkipConditionalNonString
{
    /**
     * @param false|float|int|string $data
     *
     * @return bool|float|int|string
     */
    function run($data)
    {
        if (is_string($data)) {
            $data = sprintf('"%s"', $data);
        }

        return $data;
    }
}
