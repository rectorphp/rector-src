<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

/**
 * @immutable
 */
class ImmutableClassPublicPropertyUselessVar
{
    /**
     * @var string
     */
    public $config;

    public function __construct(string $s)
    {
        $this->config = $s;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

/**
 * @immutable
 */
class ImmutableClassPublicPropertyUselessVar
{
    public string $config;

    public function __construct(string $s)
    {
        $this->config = $s;
    }
}

?>
