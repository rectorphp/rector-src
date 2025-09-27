<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class UniqueTypes
{
    public function run()
    {
        $this->getById(5);
        $this->getById(null);
        $this->getById(5);
    }

    private function getById($id)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class UniqueTypes
{
    public function run()
    {
        $this->getById(5);
        $this->getById(null);
        $this->getById(5);
    }

    private function getById(?int $id)
    {
    }
}

?>
