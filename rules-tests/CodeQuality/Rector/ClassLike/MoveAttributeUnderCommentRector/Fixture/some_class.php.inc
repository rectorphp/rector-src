<?php

namespace Rector\Tests\CodeQuality\Rector\ClassLike\MoveAttributeUnderCommentRector\Fixture;

#[Attribute]
/**
 * @property-read $name
 */
class SomeClass
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassLike\MoveAttributeUnderCommentRector\Fixture;

/**
 * @property-read $name
 */
#[Attribute]
class SomeClass
{
}

?>
