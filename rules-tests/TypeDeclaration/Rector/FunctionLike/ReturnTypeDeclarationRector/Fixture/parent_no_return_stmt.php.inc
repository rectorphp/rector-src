<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

use stdClass;

/**
 * Both class parent and child need to be in same fixture file
 * as the changed part is parent
 */
class ParentNoReturnStmt
{
    /**
     * @return stdClass
     */
    public function run()
    {
        // this method is optional, not marked as abstract
        // and leave the child to implements or not
    }
}

final class SomeChildOverrideParentMethod extends ParentNoReturnStmt
{
    public function run()
    {
        return new stdClass;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

use stdClass;

/**
 * Both class parent and child need to be in same fixture file
 * as the changed part is parent
 */
class ParentNoReturnStmt
{
    /**
     * @return stdClass
     */
    public function run()
    {
        // this method is optional, not marked as abstract
        // and leave the child to implements or not
    }
}

final class SomeChildOverrideParentMethod extends ParentNoReturnStmt
{
    public function run(): \stdClass
    {
        return new stdClass;
    }
}

?>