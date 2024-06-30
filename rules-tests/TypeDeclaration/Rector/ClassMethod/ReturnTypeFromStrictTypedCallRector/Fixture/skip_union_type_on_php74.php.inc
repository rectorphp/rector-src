<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SkipUnionTypesOnPHP74
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
