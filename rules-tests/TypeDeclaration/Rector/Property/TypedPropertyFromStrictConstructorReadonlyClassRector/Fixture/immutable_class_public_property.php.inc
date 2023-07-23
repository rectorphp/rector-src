<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector\Fixture;

/**
 * @immutable
 */
class ImmutableClassPublicProperty
{
    public $config;

    public function __construct(string $s)
    {
        $this->config = $s;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector\Fixture;

/**
 * @immutable
 */
class ImmutableClassPublicProperty
{
    public string $config;

    public function __construct(string $s)
    {
        $this->config = $s;
    }
}

?>
