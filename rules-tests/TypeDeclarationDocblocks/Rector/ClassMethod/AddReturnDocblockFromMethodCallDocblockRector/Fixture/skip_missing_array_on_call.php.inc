<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Source\SomeRepository;

final class SkipMissingArrayOnCall
{
    private SomeRepository $someRepository;

    public function __construct(SomeRepository $someRepository)
    {
        $this->someRepository = $someRepository;
    }

    public function getAll(): array
    {
        return $this->someRepository->findAllWithoutArray();
    }
}
