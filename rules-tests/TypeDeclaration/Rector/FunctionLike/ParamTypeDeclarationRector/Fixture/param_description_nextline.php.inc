<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ParamTypeDeclarationRector\Fixture;

final class DemoFile
{
    /**
     * @param int $foo
     *   My description
     * @param int $foo2 a
     *   My description
     */
    public function run($foo, $foo2)
    {
        return $foo;
    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ParamTypeDeclarationRector\Fixture;

final class DemoFile
{
    /**
     * @param int $foo
     *   My description
     * @param int $foo2 a
     *   My description
     */
    public function run(int $foo, int $foo2)
    {
        return $foo;
    }
}
?>
