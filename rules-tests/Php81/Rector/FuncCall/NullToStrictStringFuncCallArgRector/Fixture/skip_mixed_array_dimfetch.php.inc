<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipMixedArrayDimFetch
{
    public function __construct(
        private string $find,
        private string $replace,
        private array $items,
    ) {}

    public function run($index)
    {
        return str_replace($this->find, $this->replace, $this->items[$index]);
    }
}
