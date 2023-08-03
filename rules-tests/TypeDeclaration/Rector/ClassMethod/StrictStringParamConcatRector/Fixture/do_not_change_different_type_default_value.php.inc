<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

final class DoNotChangeDifferentTypeDefaultValue
{
    public static function getOrderBy($orderby = 0, $direction = 'ASC', $default = '')
    {
        if (! empty($orderby)) {
            return $orderby.' '.$direction;
        }

        return $default;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

final class DoNotChangeDifferentTypeDefaultValue
{
    public static function getOrderBy($orderby = 0, string $direction = 'ASC', $default = '')
    {
        if (! empty($orderby)) {
            return $orderby.' '.$direction;
        }

        return $default;
    }
}

?>
