<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\FixturePhp74;

class NonFinalClassOnPhp74
{
    public function run()
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\FixturePhp74;

class NonFinalClassOnPhp74
{
    public function run(): self
    {
        return $this;
    }
}

?>
