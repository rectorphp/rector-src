<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class MultipleUnionTypes
{
    public function getTogether()
    {
        if (mt_rand(0, 1)) {
            return $this->getValue();
        }

        return $this->getNextValue();
    }

    private function getValue(): int|string
    {
    }

    private function getNextValue(): float|string
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class MultipleUnionTypes
{
    public function getTogether(): int|string|float
    {
        if (mt_rand(0, 1)) {
            return $this->getValue();
        }

        return $this->getNextValue();
    }

    private function getValue(): int|string
    {
    }

    private function getNextValue(): float|string
    {
    }
}

?>
