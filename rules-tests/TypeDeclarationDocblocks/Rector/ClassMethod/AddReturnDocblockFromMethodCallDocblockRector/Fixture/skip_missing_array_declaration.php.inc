<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Source\SomeRepository;

final class SkipMissingArrayDeclaration
{
    private SomeRepository $someRepository;

    public function __construct(SomeRepository $someRepository)
    {
        $this->someRepository = $someRepository;
    }

    public function getAll()
    {
        return $this->someRepository->findAll();
    }
}
