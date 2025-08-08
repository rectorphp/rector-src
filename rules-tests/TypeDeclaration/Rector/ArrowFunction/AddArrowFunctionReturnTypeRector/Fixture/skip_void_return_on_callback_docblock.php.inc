<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

final class SkipVoidReturnOnCallbackDocblock
{
    public function doNothing(string $a): void
    {
        echo $a;
    }

    public function run()
    {
        fn() => $this->executesCallback(fn() => $this->doNothing('a'));
    }

    /**
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function executesCallback(callable $callback): mixed
    {
        return $callback();
    }
}
