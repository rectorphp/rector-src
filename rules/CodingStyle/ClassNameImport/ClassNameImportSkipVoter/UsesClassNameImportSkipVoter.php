<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport\ClassNameImportSkipVoter;

use PhpParser\Node;
use Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\ValueObject\Application\File;

/**
 * This prevents importing:
 * - App\Some\Product
 *
 * if there is already:
 * - use App\Another\Product
 */
final readonly class UsesClassNameImportSkipVoter implements ClassNameImportSkipVoterInterface
{
    public function __construct(
        private UseNodesToAddCollector $useNodesToAddCollector
    ) {
    }

    public function shouldSkip(File $file, FullyQualifiedObjectType $fullyQualifiedObjectType, Node $node): bool
    {
        $useImportTypes = $this->useNodesToAddCollector->getUseImportTypesByNode($file);

        foreach ($useImportTypes as $useImportType) {
            if (! $useImportType->equals($fullyQualifiedObjectType) && $useImportType->areShortNamesEqual(
                $fullyQualifiedObjectType
            )) {
                return true;
            }

            if ($useImportType->equals($fullyQualifiedObjectType)) {
                return false;
            }
        }

        return false;
    }
}
