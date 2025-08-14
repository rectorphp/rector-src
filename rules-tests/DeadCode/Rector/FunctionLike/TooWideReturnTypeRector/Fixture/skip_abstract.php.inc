<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

abstract class SkipAbstractClass
{
    abstract public function process(): string|int|array;

    public function getData(): string|int|bool
    {
        return 'data';
    }
}

interface SkipInterface
{
    public function handle(): string|int|null;
}

trait SkipTrait
{
    public function process(): string|array|null
    {
        return 'processed';
    }
}

?>
