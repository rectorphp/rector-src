<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class ProtectedMethodInFinalClassWithoutBaseClass
{
    public function runFirst(string $data)
    {
        $this->process($data);
    }

    protected function process($data)
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class ProtectedMethodInFinalClassWithoutBaseClass
{
    public function runFirst(string $data)
    {
        $this->process($data);
    }

    protected function process(string $data)
    {
    }
}

?>
