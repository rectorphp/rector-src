<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector\Fixture;

final class SkipYield
{
    public function someFunction(string $type): array
    {
        return collect($this->types, static function (SomeClass $parameterType, int $index) use ($type) {
            if ($parameterType::class === $type) {
                yield $index;
            }
        });
    }
}