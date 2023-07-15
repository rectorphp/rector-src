<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class CallInTernaryVar
{
    public function getValue()
    {
        return $this->get() ?: null;
    }

    public function get(): CallInTernaryVar|false {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class CallInTernaryVar
{
    public function getValue(): ?\Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture\CallInTernaryVar
    {
        return $this->get() ?: null;
    }

    public function get(): CallInTernaryVar|false {}
}

?>
