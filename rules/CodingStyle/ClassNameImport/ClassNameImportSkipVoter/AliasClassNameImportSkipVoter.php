<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport\ClassNameImportSkipVoter;

use PhpParser\Node;
use Rector\CodingStyle\ClassNameImport\AliasUsesResolver;
use Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

/**
 * Prevents adding:
 *
 * use App\SomeClass;
 *
 * If there is already:
 *
 * use App\Something as SomeClass;
 */
final class AliasClassNameImportSkipVoter implements ClassNameImportSkipVoterInterface
{
    public function __construct(
        private readonly AliasUsesResolver $aliasUsesResolver
    ) {
    }

    public function shouldSkip(File $file, FullyQualifiedObjectType $fullyQualifiedObjectType, Node $node): bool
    {
        $aliasedUses = $this->aliasUsesResolver->resolveFromNode($node, $file->getNewStmts());
        $shortNameLowered = $fullyQualifiedObjectType->getShortNameLowered();

        foreach ($aliasedUses as $aliasedUse) {
            $aliasedUseLowered = strtolower($aliasedUse);

            // its aliased, we cannot just rename it
            if (\str_ends_with($aliasedUseLowered, '\\' . $shortNameLowered)) {
                return true;
            }
        }

        return false;
    }
}
