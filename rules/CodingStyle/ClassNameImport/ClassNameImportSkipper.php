<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\ValueObject\Application\File;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class ClassNameImportSkipper
{
    /**
     * @param ClassNameImportSkipVoterInterface[] $classNameImportSkipVoters
     */
    public function __construct(
        private readonly iterable $classNameImportSkipVoters,
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
        private readonly UseImportsResolver $useImportsResolver
    ) {
    }

    public function shouldSkipNameForFullyQualifiedObjectType(
        File $file,
        Node $node,
        FullyQualifiedObjectType $fullyQualifiedObjectType
    ): bool {
        foreach ($this->classNameImportSkipVoters as $classNameImportSkipVoter) {
            if ($classNameImportSkipVoter->shouldSkip($file, $fullyQualifiedObjectType, $node)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Use_[]|GroupUse[] $uses
     */
    public function shouldImportName(Name $name, array $uses): bool
    {
        if (substr_count($name->toCodeString(), '\\') <= 1) {
            return true;
        }

        $stringName = $name->toString();
        $lastUseName = $name->getLast();
        $nameLastName = strtolower($lastUseName);

        foreach ($uses as $use) {
            $prefix = $this->useImportsResolver->resolvePrefix($use);
            $useName = $prefix . $stringName;

            foreach ($use->uses as $useUse) {
                $useUseLastName = strtolower($useUse->name->getLast());

                if ($useUseLastName !== $nameLastName) {
                    continue;
                }

                if ($this->isJustRenamedClass($stringName, $prefix, $useUse)) {
                    continue;
                }

                if ($this->isConflictedShortNameInUse($useUse, $useName, $lastUseName, $stringName)) {
                    return false;
                }

                return $prefix . $useUse->name->toString() === $stringName;
            }
        }

        return true;
    }

    private function isConflictedShortNameInUse(
        UseUse $useUse,
        string $useName,
        string $lastUseName,
        string $stringName
    ): bool {
        if (! $useUse->alias instanceof Identifier && $useName !== $stringName && $lastUseName === $stringName) {
            return true;
        }

        return $useUse->alias instanceof Identifier && $useUse->alias->toString() === $stringName;
    }

    private function isJustRenamedClass(string $stringName, string $prefix, UseUse $useUse): bool
    {
        $useUseNameString = $prefix . $useUse->name->toString();

        // is in renamed classes? skip it
        foreach ($this->renamedClassesDataCollector->getOldToNewClasses() as $oldClass => $newClass) {
            // is class being renamed in use imports?
            if ($stringName !== $newClass) {
                continue;
            }

            if ($useUseNameString !== $oldClass) {
                continue;
            }

            return true;
        }

        return false;
    }
}
