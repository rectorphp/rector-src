<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

/** @return array<array{a: int, b: float}>|null */
function foo3(int $a, float $b): ?array
{
    $bar = null;

    if ($a > 0) {
        for ($i = 0; $i < $a; $i++) {
            $bar[] = [
                'a' => $a,
                'b' => $b,
            ];
        }
    }

    return $bar;
}
