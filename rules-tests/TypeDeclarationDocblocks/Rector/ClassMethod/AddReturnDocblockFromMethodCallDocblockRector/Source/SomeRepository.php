<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Source;

final class SomeRepository
{
    /**
     * @return SomeEntity[]
     */
    public function findAll(): array
    {
        return [];
    }

    /**
     * @return SomeEntity[]
     */
    public function findAllWithoutArray()
    {
        return [];
    }

    /**
     * @return SomeEntity[]
     */
    public static function staticFindAll(): array
    {
        return [];
    }
}
