<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureInlinePublicMixed;

abstract class SomeParent
{
    /**
     * @var mixed
     */
    protected $property;
}

final class ProtectedMixedTypeExtendsChanged extends SomeParent
{
    /**
     * @var mixed
     */
    protected $property;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureInlinePublicMixed;

abstract class SomeParent
{
    protected mixed $property;
}

final class ProtectedMixedTypeExtendsChanged extends SomeParent
{
    protected mixed $property;
}

?>