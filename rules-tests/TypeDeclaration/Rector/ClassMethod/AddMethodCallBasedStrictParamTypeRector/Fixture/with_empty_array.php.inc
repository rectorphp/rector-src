<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class WithEmptyArray
{
    public function runFirst()
    {
        $this->process([]);
    }

    private function process($data)
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class WithEmptyArray
{
    public function runFirst()
    {
        $this->process([]);
    }

    private function process(array $data)
    {
    }
}

?>
