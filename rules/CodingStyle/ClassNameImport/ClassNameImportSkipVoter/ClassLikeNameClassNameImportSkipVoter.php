<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport\ClassNameImportSkipVoter;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use Rector\CodingStyle\ClassNameImport\ShortNameResolver;
use Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\ValueObject\Application\File;

/**
 * Prevents adding:
 *
 * use App\SomeClass;
 *
 * If there is already:
 *
 * class SomeClass {}
 */
final readonly class ClassLikeNameClassNameImportSkipVoter implements ClassNameImportSkipVoterInterface
{
    public function __construct(
        private ShortNameResolver $shortNameResolver
    ) {
    }

    public function shouldSkip(File $file, FullyQualifiedObjectType $fullyQualifiedObjectType, Node $node): bool
    {
        $classLikeNames = $this->shortNameResolver->resolveShortClassLikeNames($file);
        if ($classLikeNames === []) {
            return false;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        $namespace = $scope instanceof Scope ? $scope->getNamespace() : null;
        $namespace = strtolower((string) $namespace);

        $shortNameLowered = $fullyQualifiedObjectType->getShortNameLowered();
        $fullyQualifiedObjectTypeNamespace = strtolower(
            substr($fullyQualifiedObjectType->getClassName(), 0, -strlen($fullyQualifiedObjectType->getShortName()) - 1)
        );

        foreach ($classLikeNames as $classLikeName) {
            if (strtolower($classLikeName) !== $shortNameLowered) {
                continue;
            }

            if ($namespace === '') {
                return true;
            }

            if ($namespace !== $fullyQualifiedObjectTypeNamespace) {
                return true;
            }
        }

        return false;
    }
}
