<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport\ClassNameImportSkipVoter;

use Nette\Utils\Strings;
use PhpParser\Node;
use Rector\CodingStyle\ClassNameImport\ShortNameResolver;
use Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\ValueObject\Application\File;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

/**
 * Prevents adding:
 *
 * use App\SomeClass;
 *
 * If there is already:
 *
 * SomeClass::callThis();
 */
final class FullyQualifiedNameClassNameImportSkipVoter implements ClassNameImportSkipVoterInterface
{
    public function __construct(
        private readonly ShortNameResolver $shortNameResolver,
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector
    ) {
    }

    public function shouldSkip(File $file, FullyQualifiedObjectType $fullyQualifiedObjectType, Node $node): bool
    {
        // "new X" or "X::static()"
        /** @var array<string, string> $shortNamesToFullyQualifiedNames */
        $shortNamesToFullyQualifiedNames = $this->shortNameResolver->resolveFromFile($file);
        $removedUses = $this->renamedClassesDataCollector->getOldClasses();
        $fullyQualifiedObjectTypeShortName = $fullyQualifiedObjectType->getShortName();

        foreach ($shortNamesToFullyQualifiedNames as $shortName => $fullyQualifiedName) {
            if ($fullyQualifiedObjectTypeShortName !== $shortName) {
                $shortName = str_contains($shortName, '\\')
                    ? ltrim((string) Strings::after($shortName, '\\', -1))
                    : $shortName;
            }

            if ($fullyQualifiedObjectTypeShortName !== $shortName) {
                continue;
            }

            $fullyQualifiedName = ltrim($fullyQualifiedName, '\\');
            if (in_array($fullyQualifiedName, $removedUses, true)) {
                return false;
            }

            if (! str_contains($fullyQualifiedName, '\\')) {
                return $shortName !== $fullyQualifiedName;
            }

            return $fullyQualifiedObjectType->getClassName() !== $fullyQualifiedName;
        }

        return false;
    }
}
