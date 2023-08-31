<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

class BaseClassFinder {
    public function find_object($conditions): ?static
    {
    }
}


final class SubClassFinder extends BaseClassFinder
{
    public function findByCode($code)
    {
        if (false === $code) {
            return null;
        }
        return $this->find_object($code);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

class BaseClassFinder {
    public function find_object($conditions): ?static
    {
    }
}


final class SubClassFinder extends BaseClassFinder
{
    public function findByCode($code): ?static
    {
        if (false === $code) {
            return null;
        }
        return $this->find_object($code);
    }
}

?>
