<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

final class SkipVoidReturnOnCallbackDockblock
{
    public function doNohing(string $a): void
    {
        echo $a;
    }

    public function run()
    {
        fn() => $this->executesCallback(fn() => $this->doNohing('a'));
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
