<?php

declare(strict_types=1);

namespace Rector\Naming\PropertyRenamer;

use PhpParser\Node\Stmt\Property;
use PhpParser\Node\VarLikeIdentifier;
use Rector\Naming\Guard\PropertyConflictingNameGuard\MatchPropertyTypeConflictingNameGuard;
use Rector\Naming\RenameGuard\PropertyRenameGuard;
use Rector\Naming\ValueObject\PropertyRename;

final readonly class MatchTypePropertyRenamer
{
    public function __construct(
        private MatchPropertyTypeConflictingNameGuard $matchPropertyTypeConflictingNameGuard,
        private PropertyRenameGuard $propertyRenameGuard,
        private PropertyFetchRenamer $propertyFetchRenamer,
    ) {
    }

    public function rename(PropertyRename $propertyRename): ?Property
    {
        if ($this->matchPropertyTypeConflictingNameGuard->isConflicting($propertyRename)) {
            return null;
        }

        if ($propertyRename->isAlreadyExpectedName()) {
            return null;
        }

        if ($this->propertyRenameGuard->shouldSkip($propertyRename)) {
            return null;
        }

        $onlyPropertyProperty = $propertyRename->getPropertyProperty();
        $onlyPropertyProperty->name = new VarLikeIdentifier($propertyRename->getExpectedName());
        $this->renamePropertyFetchesInClass($propertyRename);

        return $propertyRename->getProperty();
    }

    private function renamePropertyFetchesInClass(PropertyRename $propertyRename): void
    {
        $this->propertyFetchRenamer->renamePropertyFetchesInClass(
            $propertyRename->getClassLike(),
            $propertyRename->getCurrentName(),
            $propertyRename->getExpectedName()
        );
    }
}
