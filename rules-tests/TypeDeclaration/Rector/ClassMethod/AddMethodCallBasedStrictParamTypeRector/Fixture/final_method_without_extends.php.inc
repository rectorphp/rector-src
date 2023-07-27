<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

class FinalProtectedMethodInClassWithoutBaseClass
{
    public function runFirst(string $data)
    {
        $this->process($data);
    }

    final protected function process($data)
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

class FinalProtectedMethodInClassWithoutBaseClass
{
    public function runFirst(string $data)
    {
        $this->process($data);
    }

    final protected function process(string $data)
    {
    }
}

?>
