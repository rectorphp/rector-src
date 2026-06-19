<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\Guard;

use PHPStan\Type\Generic\TemplateType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

final class TemplateTypeRemovalGuard
{
    public function isLegal(Type $docType): bool
    {
        // cover direct \PHPStan\Type\Generic\TemplateUnionType
        if ($docType instanceof TemplateType) {
            return false;
        }

        // cover mixed template with mix from @template and non @template
        $types = $docType instanceof UnionType
            ? $docType->getTypes()
            : [$docType];
<<<<<<< HEAD
        return array_all($types, fn (Type $type): bool => ! $type instanceof TemplateType);
=======
        return array_all($types, fn ($type): bool => ! $type instanceof TemplateType);
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
    }
}
