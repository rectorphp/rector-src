<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Node;

use PhpParser\Node\Name;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class NameImporter
{
    public function __construct(
        private readonly ClassNameImportSkipper $classNameImportSkipper,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly UseNodesToAddCollector $useNodesToAddCollector,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function importName(Name $name, File $file): ?Name
    {
        if ($this->shouldSkipName($name)) {
            return null;
        }

        $staticType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($name);
        if (! $staticType instanceof FullyQualifiedObjectType) {
            return null;
        }

        return $this->importNameAndCollectNewUseStatement($file, $name, $staticType);
    }

    private function shouldSkipName(Name $name): bool
    {
        $virtualNode = (bool) $name->getAttribute(AttributeKey::VIRTUAL_NODE);
        if ($virtualNode) {
            return true;
        }

        // is scalar name?
        if (in_array($name->toLowerString(), ['true', 'false', 'bool'], true)) {
            return true;
        }

        // namespace <name>
        // use <name>;
        if ($this->isNamespaceOrUseImportName($name)) {
            return true;
        }

        if ($this->isFunctionOrConstantImportWithSingleName($name)) {
            return true;
        }

        // Importing root namespace classes (like \DateTime) is optional
        if (! SimpleParameterProvider::provideBoolParameter(Option::IMPORT_SHORT_CLASSES)) {
            $stringName = $name->toString();
            return substr_count($stringName, '\\') === 0;
        }

        if ($name->getAttribute(AttributeKey::IS_FUNCCALL_NAME) !== true) {
            return false;
        }

        $scope = $name->getAttribute(AttributeKey::SCOPE);
        if ($scope === null) {
            return false;
        }

        $namespace = $scope->getNamespace();
        $prefix = $namespace === null ? '' : $namespace . '\\';
        $lastName = $name->getLast();

        if ($prefix . $lastName === $name->toString()) {
            return false;
        }

        $functionName = new Name($prefix . $lastName);
        if ($this->reflectionProvider->hasFunction($functionName, $scope)) {
            $function = $this->reflectionProvider->getFunction($functionName, $scope);
            return ! $function instanceof NativeFunctionReflection;
        }

        return false;
    }

    private function importNameAndCollectNewUseStatement(
        File $file,
        Name $name,
        FullyQualifiedObjectType $fullyQualifiedObjectType
    ): ?Name {
        // the same end is already imported → skip
        if ($this->classNameImportSkipper->shouldSkipNameForFullyQualifiedObjectType(
            $file,
            $name,
            $fullyQualifiedObjectType
        )) {
            return null;
        }

        if ($this->useNodesToAddCollector->isShortImported($file, $fullyQualifiedObjectType)) {
            if ($this->useNodesToAddCollector->isImportShortable($file, $fullyQualifiedObjectType)) {
                return $fullyQualifiedObjectType->getShortNameNode();
            }

            return null;
        }

        $this->addUseImport($file, $name, $fullyQualifiedObjectType);
        return $fullyQualifiedObjectType->getShortNameNode();
    }

    /**
     * Skip:
     * - namespace name
     * - use import name
     */
    private function isNamespaceOrUseImportName(Name $name): bool
    {
        if ($name->getAttribute(AttributeKey::IS_NAMESPACE_NAME) === true) {
            return true;
        }

        return $name->getAttribute(AttributeKey::IS_USEUSE_NAME) === true;
    }

    private function isFunctionOrConstantImportWithSingleName(Name $name): bool
    {
        if ($name->getAttribute(AttributeKey::IS_CONSTFETCH_NAME) === true) {
            return count($name->getParts()) === 1;
        }

        if ($name->getAttribute(AttributeKey::IS_FUNCCALL_NAME) === true) {
            return count($name->getParts()) === 1;
        }

        return false;
    }

    private function addUseImport(File $file, Name $name, FullyQualifiedObjectType $fullyQualifiedObjectType): void
    {
        if ($this->useNodesToAddCollector->hasImport($file, $name, $fullyQualifiedObjectType)) {
            return;
        }

        if ($name->getAttribute(AttributeKey::IS_FUNCCALL_NAME) === true) {
            $this->useNodesToAddCollector->addFunctionUseImport($fullyQualifiedObjectType);
        } elseif ($name->getAttribute(AttributeKey::IS_CONSTFETCH_NAME) === true) {
            $this->useNodesToAddCollector->addConstantUseImport($fullyQualifiedObjectType);
        } else {
            $this->useNodesToAddCollector->addUseImport($fullyQualifiedObjectType);
        }
    }
}
