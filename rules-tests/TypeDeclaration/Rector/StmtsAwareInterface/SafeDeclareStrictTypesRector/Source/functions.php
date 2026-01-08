<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source;

function acceptsInt(int $value): void
{
}

function acceptsString(string $value): void
{
}

function acceptsFloat(float $value): void
{
}

function acceptsCallable(callable $fn): void
{
}

function acceptsNullableString(?string $value): void
{
}

function acceptsStringOrInt(string|int $value): void
{
}

function sumInts(int ...$numbers): int
{
    return array_sum($numbers);
}
