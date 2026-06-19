<?php

declare(strict_types=1);

namespace Rector\DeadCode\TypeNodeAnalyzer;

use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;

final class GenericTypeNodeAnalyzer
{
    public function hasGenericType(BracketsAwareUnionTypeNode $bracketsAwareUnionTypeNode): bool
    {
        $types = $bracketsAwareUnionTypeNode->types;
<<<<<<< HEAD
        return array_any($types, fn (TypeNode $typeNode): bool => $typeNode instanceof GenericTypeNode);
=======
        return array_any($types, fn ($type): bool => $type instanceof GenericTypeNode);
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
    }
}
