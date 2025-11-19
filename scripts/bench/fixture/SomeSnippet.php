<?php

declare(strict_types=1);

namespace fixture;

final class SomeSnippet
{
    public function someMethod(string $param, int $anotherParam): void
    {
        foreach ([1, 2, 3] as $item) {
            echo $item * $anotherParam . ' ' . $param . PHP_EOL;
        }
    }
}
