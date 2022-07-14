<?php

declare(strict_types=1);

namespace Rector\Naming\RenameGuard;

use Rector\Naming\Contract\Guard\ConflictingNameGuardInterface;
use Rector\Naming\Contract\RenameValueObjectInterface;
use Rector\Naming\ValueObject\PropertyRename;

final class PropertyRenameGuard
{
    /**
     * @param ConflictingNameGuardInterface[] $conflictingNameGuards
     */
    public function __construct(
        private readonly array $conflictingNameGuards
    ) {
    }

    public function shouldSkip(PropertyRename $propertyRename): bool
    {
        foreach ($this->conflictingNameGuards as $conflictingNameGuard) {
            if ($conflictingNameGuard->isConflicting($propertyRename)) {
                return true;
            }
        }

        return false;
    }
}
