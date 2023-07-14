<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SameTypedStringReturns
{
    public function getData()
    {
        if (rand(0,1)) {
            return 'hallo';
        }

        return $this->getString();
    }

    public function getString(): string
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SameTypedStringReturns
{
    public function getData(): string
    {
        if (rand(0,1)) {
            return 'hallo';
        }

        return $this->getString();
    }

    public function getString(): string
    {

    }
}

?>
