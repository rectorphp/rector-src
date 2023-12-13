<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector\Fixture;

final class OnElseMethodCall
{
    public function run()
    {
        if (rand(0, 1)) {
            return true;
        }

        return $this->execute();
    }

    public function execute(): bool
    {
        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector\Fixture;

final class OnElseMethodCall
{
    public function run(): bool
    {
        if (rand(0, 1)) {
            return true;
        }

        return $this->execute();
    }

    public function execute(): bool
    {
        return true;
    }
}

?>
