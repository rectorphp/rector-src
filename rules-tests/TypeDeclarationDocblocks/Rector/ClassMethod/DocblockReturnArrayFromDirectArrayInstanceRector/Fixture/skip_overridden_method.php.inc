<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

interface SomeOverriddenInterface
{
    /**
     * @return int[]
     */
    public function getCodes(): iterable;
}

abstract class SomeOverriddenBase
{
    /**
     * @return string[]
     */
    abstract public function getMethods(): iterable;
}

final class SomeOverriddenChild extends SomeOverriddenBase implements SomeOverriddenInterface
{
    public function getCodes(): iterable
    {
        return [
            200,
            201,
        ];
    }

    public function getMethods(): iterable
    {
        return [
            'GET',
            'POST',
        ];
    }
}
