<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

class SkipMultiSprintfPossiblyFalsy
{
    public function columnExpr($columnExpr, string $default): string
    {
        if (rand(0, 1)) {
            return sprintf("hello %s", $columnExpr);
        }

        // $columnExpr can be anything empty: false | null | '' | 0 | '0' | []
        if (empty($columnExpr)) {
            return $default;
        }

        return sprintf("hello %s", $columnExpr);
    }
}