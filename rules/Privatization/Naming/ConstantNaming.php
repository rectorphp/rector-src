<?php

declare(strict_types=1);

namespace Rector\Privatization\Naming;

use PhpParser\Node\Stmt\PropertyProperty;
use Rector\NodeNameResolver\NodeNameResolver;
use Symfony\Component\String\UnicodeString;

final class ConstantNaming
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function createFromProperty(PropertyProperty $propertyProperty): string
    {
        /** @var string $propertyName */
        $propertyName = $this->nodeNameResolver->getName($propertyProperty);
        return $this->createUnderscoreUppercaseString($propertyName);
    }

    private function createUnderscoreUppercaseString(string $propertyName): string
    {
        $propertyNameUnicodeString = new UnicodeString($propertyName);
        return $propertyNameUnicodeString->snake()
            ->upper()
            ->toString();
    }
}
