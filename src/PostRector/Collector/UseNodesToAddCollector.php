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
    private array $constantUseImportTypesInFilePath = [];

    /**
     * @var FullyQualifiedObjectType[]
     */
    private array $functionUseImportTypesInFilePath = [];

    /**
     * @var FullyQualifiedObjectType[]
     */
    private array $useImportTypesInFilePath = [];

    public function __construct(
        private readonly UseImportsResolver $useImportsResolver,
    ) {
    }

    public function addUseImport(FullyQualifiedObjectType $fullyQualifiedObjectType): void
    {
        $this->useImportTypesInFilePath[] = $fullyQualifiedObjectType;
    }

    public function addConstantUseImport(FullyQualifiedObjectType $fullyQualifiedObjectType): void
    {
        $this->constantUseImportTypesInFilePath[] = $fullyQualifiedObjectType;
    }

    public function addFunctionUseImport(FullyQualifiedObjectType $fullyQualifiedObjectType): void
    {
        $this->functionUseImportTypesInFilePath[] = $fullyQualifiedObjectType;
    }

    /**
     * @return AliasedObjectType[]|FullyQualifiedObjectType[]
     */
    public function getUseImportTypesByNode(): array
    {
        $objectTypes = $this->useImportTypesInFilePath;

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
        $useImports = $this->getUseImportTypesByNode();

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

        $fileConstantUseImportTypes = $this->constantUseImportTypesInFilePath;

        foreach ($fileConstantUseImportTypes as $fileConstantUseImportType) {
            // don't compare strtolower for use const as insensitive is allowed, see https://3v4l.org/lteVa
            if ($fileConstantUseImportType->getShortName() === $shortName) {
                return true;
            }
        }

        $shortName = strtolower($shortName);
        if ($this->isShortClassImported($shortName)) {
            return true;
        }

        $fileFunctionUseImportTypes = $this->functionUseImportTypesInFilePath;
        foreach ($fileFunctionUseImportTypes as $fileFunctionUseImportType) {
            if (strtolower($fileFunctionUseImportType->getShortName()) === $shortName) {
                return true;
            }
        }

        return false;
    }

    public function isImportShortable(FullyQualifiedObjectType $fullyQualifiedObjectType): bool
    {
        $fileUseImportTypes = $this->useImportTypesInFilePath;

        foreach ($fileUseImportTypes as $fileUseImportType) {
            if ($fullyQualifiedObjectType->equals($fileUseImportType)) {
                return true;
            }
        }

        $constantImports = $this->constantUseImportTypesInFilePath;
        foreach ($constantImports as $constantImport) {
            if ($fullyQualifiedObjectType->equals($constantImport)) {
                return true;
            }
        }

        $functionImports = $this->functionUseImportTypesInFilePath;
        foreach ($functionImports as $functionImport) {
            if ($fullyQualifiedObjectType->equals($functionImport)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return AliasedObjectType[]|FullyQualifiedObjectType[]
     */
    public function getObjectImportsByFilePath(): array
    {
        return $this->useImportTypesInFilePath;
    }

    /**
     * @return FullyQualifiedObjectType[]
     */
    public function getConstantImportsByFilePath(): array
    {
        return $this->constantUseImportTypesInFilePath;
    }

    /**
     * @return FullyQualifiedObjectType[]
     */
    public function getFunctionImportsByFilePath(): array
    {
        return $this->functionUseImportTypesInFilePath;
    }

    private function isShortClassImported(string $shortName): bool
    {
        $fileUseImports = $this->useImportTypesInFilePath;

        foreach ($fileUseImports as $fileUseImport) {
            if (strtolower($fileUseImport->getShortName()) === $shortName) {
                return true;
            }
        }

        return false;
    }

    public function reset(): void
    {
        $this->useImportTypesInFilePath = [];
        $this->constantUseImportTypesInFilePath = [];
        $this->functionUseImportTypesInFilePath = [];
    }
}
