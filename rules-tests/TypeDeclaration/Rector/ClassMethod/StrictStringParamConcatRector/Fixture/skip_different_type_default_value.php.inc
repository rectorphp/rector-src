<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

final class SkipDifferentTypeDefaultValue
{
    public static function getOrderBy($orderby = 0, $direction = 'ASC', $default = '')
    {
        if (! empty($orderby)) {
            return $orderby.' '.$direction;
        }

        return $default;
    }
}
