<?php

declare(strict_types=1);

namespace Rector\PostRector\Collector;

use PhpParser\Node\Identifier;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class UseNodesToAddCollector
{
    /**
     * @var FullyQualifiedObjectType[]
     */
    private array $constantUseImportTypes = [];

    /**
     * @var FullyQualifiedObjectType[]
     */
    private array $functionUseImportTypes = [];

    /**
     * @var FullyQualifiedObjectType[]
     */
    private array $useImportTypes = [];

    public function __construct(
        private readonly UseImportsResolver $useImportsResolver,
    ) {
    }

    public function addUseImport(FullyQualifiedObjectType $fullyQualifiedObjectType): void
    {
        $this->useImportTypes[] = $fullyQualifiedObjectType;
    }

    public function addConstantUseImport(FullyQualifiedObjectType $fullyQualifiedObjectType): void
    {
        $this->constantUseImportTypes[] = $fullyQualifiedObjectType;
    }

    public function addFunctionUseImport(FullyQualifiedObjectType $fullyQualifiedObjectType): void
    {
        $this->functionUseImportTypes[] = $fullyQualifiedObjectType;
    }

    /**
     * @return AliasedObjectType[]|FullyQualifiedObjectType[]
     */
    public function getUseImportTypes(): array
    {
        $objectTypes = $this->useImportTypes;

        $uses = $this->useImportsResolver->resolve();

        foreach ($uses as $use) {
            $prefix = $this->useImportsResolver->resolvePrefix($use);

            foreach ($use->uses as $useUse) {
                if ($useUse->alias instanceof Identifier) {
                    $objectTypes[] = new AliasedObjectType($useUse->alias->toString(), $prefix . $useUse->name);
                } else {
                    $objectTypes[] = new FullyQualifiedObjectType($prefix . $useUse->name);
                }
            }
        }

        return $objectTypes;
    }

    public function hasImport(
        FullyQualifiedObjectType $fullyQualifiedObjectType
    ): bool {
        $useImports = $this->getUseImportTypes();

        foreach ($useImports as $useImport) {
            if ($useImport->equals($fullyQualifiedObjectType)) {
                return true;
            }
        }

        return false;
    }

    public function isShortImported(FullyQualifiedObjectType $fullyQualifiedObjectType): bool
    {
        $shortName = $fullyQualifiedObjectType->getShortName();

        foreach ($this->constantUseImportTypes as $constantUseImportType) {
            // don't compare strtolower for use const as insensitive is allowed, see https://3v4l.org/lteVa
            if ($constantUseImportType->getShortName() === $shortName) {
                return true;
            }
        }

        $shortName = strtolower($shortName);
        if ($this->isShortClassImported($shortName)) {
            return true;
        }

        foreach ($this->functionUseImportTypes as $functionUseImportType) {
            if (strtolower($functionUseImportType->getShortName()) === $shortName) {
                return true;
            }
        }

        return false;
    }

    public function isImportShortable(FullyQualifiedObjectType $fullyQualifiedObjectType): bool
    {
        foreach ($this->useImportTypes as $useImportType) {
            if ($fullyQualifiedObjectType->equals($useImportType)) {
                return true;
            }
        }

        foreach ($this->constantUseImportTypes as $constantUseImportType) {
            if ($fullyQualifiedObjectType->equals($constantUseImportType)) {
                return true;
            }
        }

        foreach ($this->functionUseImportTypes as $functionUseImportType) {
            if ($fullyQualifiedObjectType->equals($functionUseImportType)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return AliasedObjectType[]|FullyQualifiedObjectType[]
     */
    public function getObjectImports(): array
    {
        return $this->useImportTypes;
    }

    /**
     * @return FullyQualifiedObjectType[]
     */
    public function getConstantImports(): array
    {
        return $this->constantUseImportTypes;
    }

    /**
     * @return FullyQualifiedObjectType[]
     */
    public function getFunctionImports(): array
    {
        return $this->functionUseImportTypes;
    }

    private function isShortClassImported(string $shortName): bool
    {
        foreach ($this->useImportTypes as $useImportType) {
            if (strtolower($useImportType->getShortName()) === $shortName) {
                return true;
            }
        }

        return false;
    }

    public function reset(): void
    {
        $this->useImportTypes = [];
        $this->constantUseImportTypes = [];
        $this->functionUseImportTypes = [];
    }
}
