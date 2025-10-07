<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureTrueInUnion;

/**
 * true|othertype work on >= php 8.2, ref https://3v4l.org/UJqXT
 */
final class TrueInUnion
{
    public function run($value)
    {
        if ($value) {
            return true;
        }

        return strtoupper('warning');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureTrueInUnion;

/**
 * true|othertype work on >= php 8.2, ref https://3v4l.org/UJqXT
 */
final class TrueInUnion
{
    public function run($value): true|string
    {
        if ($value) {
            return true;
        }

        return strtoupper('warning');
    }
}

?>
