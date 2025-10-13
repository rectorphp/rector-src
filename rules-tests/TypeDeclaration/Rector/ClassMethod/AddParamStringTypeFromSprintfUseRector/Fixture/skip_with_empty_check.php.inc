<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

class SkipWithEmptyCheck
{
    public function columnExpr($columnExpr, string $default): string
    {
        // $columnExpr can be anything empty: false | null | '' | 0 | '0' | []
        if (empty($columnExpr)) {
            return $default;
        }

        return sprintf("hello %s", $columnExpr);
    }
}