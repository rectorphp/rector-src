<?php

declare(strict_types=1);

namespace Rector\Naming\Guard;

use DateTimeInterface;
use PHPStan\Type\TypeWithClassName;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;
use Rector\Util\StringUtils;

final class DateTimeAtNamingConventionGuard
{
    public function __construct(
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly TypeUnwrapper $typeUnwrapper
    ) {
    }

    public function isConflicting(PropertyRename $propertyRename): bool
    {
        $type = $this->nodeTypeResolver->getType($propertyRename->getProperty());
        $type = $this->typeUnwrapper->unwrapFirstObjectTypeFromUnionType($type);

        if (! $type instanceof TypeWithClassName) {
            return false;
        }

        if (! is_a($type->getClassName(), DateTimeInterface::class, true)) {
            return false;
        }

        return StringUtils::isMatch(
            $propertyRename->getCurrentName(),
            BreakingVariableRenameGuard::AT_NAMING_REGEX . ''
        );
    }
}
