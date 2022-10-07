<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

/**
 * Both class parent and child need to be in same fixture file
 * as demonstrate the changed parts are both
 */
class ParentNoReturnStmt2
{
    public function run()
    {
        // this method is optional, not marked as abstract
        // and leave the child to implements or not
    }
}

final class SomeChildOverrideParentMethod2 extends ParentNoReturnStmt2
{
    public function run()
    {
        echo 'test';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

/**
 * Both class parent and child need to be in same fixture file
 * as demonstrate the changed parts are both
 */
class ParentNoReturnStmt2
{
    public function run(): void
    {
        // this method is optional, not marked as abstract
        // and leave the child to implements or not
    }
}

final class SomeChildOverrideParentMethod2 extends ParentNoReturnStmt2
{
    public function run(): void
    {
        echo 'test';
    }
}

?>
