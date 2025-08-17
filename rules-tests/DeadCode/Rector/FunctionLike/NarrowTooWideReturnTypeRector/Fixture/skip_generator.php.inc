<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

class SkipGenerator
{
    public function generate(): \Generator|array|null
    {
        if (rand(0, 1)) {
            yield 1;
            yield 2;
        }

        return 'test';
    }

    public function delegate(): \Generator|array
    {
        yield from [1, 2, 3];
    }
}

function generate(): \Generator|array|null
{
    if (rand(0, 1)) {
        yield 1;
        yield 2;
    }

    return 'test';
}

function delegate(): \Generator|array
{
    yield from [1, 2, 3];
}

function(): \Generator|array|null
{
    if (rand(0, 1)) {
        yield 1;
        yield 2;
    }

    return 'test';
};

function(): \Generator|array|null
{
    yield from [1, 2, 3];
};

fn(): \Iterator|array|null => yield 1;

fn(string $a): \Iterator|array|null => match ($a) {
    'a' => yield 1,
    'b' => yield 2,
    default => 'test',
};

fn(): \Iterator|array|null => yield from [1, 2, 3];

?>
