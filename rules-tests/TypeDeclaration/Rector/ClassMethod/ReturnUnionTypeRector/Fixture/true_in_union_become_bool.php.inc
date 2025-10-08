<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

/**
 * true|othertype cannot work on <=php 8.1, ref https://3v4l.org/UJqXT
 */
final class TrueInUnionBecomeBool
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
 * true|othertype cannot work on <=php 8.1, ref https://3v4l.org/UJqXT
 */
final class TrueInUnionBecomeBool
{
    public function run($value): bool|string
    {
        if ($value) {
            return true;
        }

        return substr('warning', 1);
    }
}

?>
