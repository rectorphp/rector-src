<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class WrongTypedIntConstant
{
    /**
     * @var string
     */
    const WRONG_TYPED = 0;

    public function getData()
    {
        if (rand(0,1)) {
            return self::WRONG_TYPED;
        }

        return $this->getInt();
    }

    public function getInt(): int
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class WrongTypedIntConstant
{
    /**
     * @var string
     */
    const WRONG_TYPED = 0;

    public function getData(): int
    {
        if (rand(0,1)) {
            return self::WRONG_TYPED;
        }

        return $this->getInt();
    }

    public function getInt(): int
    {

    }
}

?>
