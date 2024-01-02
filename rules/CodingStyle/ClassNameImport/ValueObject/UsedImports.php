<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport\ValueObject;

use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final readonly class UsedImports
{
    /**
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $useImports
     * @param FullyQualifiedObjectType[] $functionImports
     * @param FullyQualifiedObjectType[] $constantImports
     */
    public function __construct(
        private array $useImports,
        private array $functionImports,
        private array $constantImports
    ) {
    }

    /**
     * @return array<FullyQualifiedObjectType|AliasedObjectType>
     */
    public function getUseImports(): array
    {
        return $this->useImports;
    }

    /**
     * @return FullyQualifiedObjectType[]
     */
    public function getFunctionImports(): array
    {
        return $this->functionImports;
    }

    /**
     * @return FullyQualifiedObjectType[]
     */
    public function getConstantImports(): array
    {
        return $this->constantImports;
    }
}
