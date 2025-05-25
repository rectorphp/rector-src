<?php

declare(strict_types=1);

namespace Rector\Naming\RenameGuard;

use PHPStan\Type\ObjectType;
use Rector\Naming\Guard\DateTimeAtNamingConventionGuard;
use Rector\Naming\Guard\HasMagicGetSetGuard;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\NodeTypeResolver\NodeTypeResolver;

final readonly class PropertyRenameGuard
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver,
        private DateTimeAtNamingConventionGuard $dateTimeAtNamingConventionGuard,
        private HasMagicGetSetGuard $hasMagicGetSetGuard,
    ) {
    }

    public function shouldSkip(PropertyRename $propertyRename): bool
    {
        if (! $propertyRename->isPrivateProperty()) {
            return true;
        }

        if ($this->nodeTypeResolver->isObjectType(
            $propertyRename->getProperty(),
            new ObjectType('Ramsey\Uuid\UuidInterface')
        )) {
            return true;
        }

        if ($this->dateTimeAtNamingConventionGuard->isConflicting($propertyRename)) {
            return true;
        }

        return $this->hasMagicGetSetGuard->isConflicting($propertyRename);
    }
}
