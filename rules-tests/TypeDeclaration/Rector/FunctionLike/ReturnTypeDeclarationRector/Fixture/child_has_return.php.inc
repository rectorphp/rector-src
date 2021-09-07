<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

abstract class AbstractClass2
{
    /**
     * @param mixed[] $param
     * @return bool
     */
    public function getValue(array $param)
    {
        return rand(0, 1)
            ? true
            : false;
    }
}

class ChildHasReturn extends AbstractClass2
{
    /**
     * @param mixed[] $param
     * @return bool
     */
    public function getValue(array $param): bool
    {
        return is_array($param);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

abstract class AbstractClass2
{
    /**
     * @param mixed[] $param
     */
    public function getValue(array $param): bool
    {
        return rand(0, 1)
            ? true
            : false;
    }
}

class ChildHasReturn extends AbstractClass2
{
    /**
     * @param mixed[] $param
     * @return bool
     */
    public function getValue(array $param): bool
    {
        return is_array($param);
    }
}

?>