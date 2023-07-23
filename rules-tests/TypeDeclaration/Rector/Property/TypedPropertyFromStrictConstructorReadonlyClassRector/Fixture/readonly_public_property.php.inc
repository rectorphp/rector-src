<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector\Fixture;

class ReadonlyPublicProperty
{
    /** @readonly */
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

class ReadonlyPublicProperty
{
    /** @readonly */
    public string $config;

    public function __construct(string $s)
    {
        $this->config = $s;
    }
}

?>
