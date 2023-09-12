<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class ResolvedImports {
    /**
     * @var array<FullyQualifiedObjectType|AliasedObjectType>
     */
    private array $useImports = [];
    /**
     * @var FullyQualifiedObjectType[]
     */
    private array $functionImports = [];
    /**
     * @var FullyQualifiedObjectType[]
     */
    private array $constantImports = [];

    /**
     * @param array<FullyQualifiedObjectType|AliasedObjectType> $useImports
     * @param FullyQualifiedObjectType[] $functionImports
     * @param FullyQualifiedObjectType[] $constantImports
     */
    public function __construct(array $useImports, array $functionImports, array $constantImports)
    {
        $this->useImports = $useImports;
        $this->functionImports = $functionImports;
        $this->constantImports = $constantImports;
    }

    /**
     * @return array<FullyQualifiedObjectType|AliasedObjectType>
     */
    public function getUseImports() : array
    {
        return $this->useImports;
    }

    /**
     * @return FullyQualifiedObjectType[]
     */
    public function getFunctionImports() : array
    {
        return $this->functionImports;
    }

    /**
     * @return FullyQualifiedObjectType[]
     */
    public function getConstantImports() : array
    {
        return $this->constantImports;
    }
}
