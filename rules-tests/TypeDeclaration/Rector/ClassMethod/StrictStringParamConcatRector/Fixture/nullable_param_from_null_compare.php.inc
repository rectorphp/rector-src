<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

class NullableParamFromNullCompare
{
    public static function getOrderBy($orderby, $direction = 'ASC', $default = '')
    {
        if (null !== $orderby && ! empty($orderby)) {
            return $orderby.' '.$direction;
        }

        return $default;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

class NullableParamFromNullCompare
{
    public static function getOrderBy(?string $orderby, string $direction = 'ASC', $default = '')
    {
        if (null !== $orderby && ! empty($orderby)) {
            return $orderby.' '.$direction;
        }

        return $default;
    }
}

?>
