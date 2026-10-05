<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\FixtureUnionIntersection;

/**
 * "\Iterator|(\Iterator&\Countable)" is a compile error in PHP: the intersection is redundant
 */
final class RedundantIntersection
{
    public function run(\Iterator $obj)
    {
        if (! $obj instanceof \Countable) {
            return $obj;
        }

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
 * "\Iterator|(\Iterator&\Countable)" is a compile error in PHP: the intersection is redundant
 */
final class RedundantIntersection
{
    public function run(\Iterator $obj): \Iterator|string
    {
        if (! $obj instanceof \Countable) {
            return $obj;
        }

        if (rand(0, 1)) {
            return $obj;
        }

        return '10';
    }
}

?>
