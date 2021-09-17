<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\VarConstantCommentRector\Fixture;

class PairClassStringArray
{
    private const CLASSES = [
        \stdClass::class => PairClassStringArray::class,
    ];
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\VarConstantCommentRector\Fixture;

class PairClassStringArray
{
    /**
     * @var array<class-string, class-string>
     */
    private const CLASSES = [
        \stdClass::class => PairClassStringArray::class,
    ];
}

?>
