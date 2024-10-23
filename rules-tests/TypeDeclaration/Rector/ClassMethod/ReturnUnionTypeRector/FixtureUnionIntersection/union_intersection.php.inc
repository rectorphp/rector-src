<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureUnionIntersection;

/**
 * union intersection works on php 8.2+
 */
final class UnionIntersection
{
    public function run(\Iterator&\Countable $obj)
    {
        if (rand(0, 1)) {
            return $obj;
        }

        return '10';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureUnionIntersection;

/**
 * union intersection works on php 8.2+
 */
final class UnionIntersection
{
    public function run(\Iterator&\Countable $obj): (\Iterator&\Countable)|string
    {
        if (rand(0, 1)) {
            return $obj;
        }

        return '10';
    }
}

?>
