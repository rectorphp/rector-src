<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

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

        return substr('warning', 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

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

        return substr('warning', 1);
    }
}

?>
