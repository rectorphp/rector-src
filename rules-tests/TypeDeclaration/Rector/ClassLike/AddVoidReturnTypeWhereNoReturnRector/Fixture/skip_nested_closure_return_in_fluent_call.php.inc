<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class SkipNestedClosureReturnInFluentCall
{
    public function something($callback = null): self
    {
        return $this;
    }

    public function run(): void
    {
        $model = $this->something()->value();
        $model = $this->something(
            function ($query) {
                return $query;
            }
        )->value();
    }

    private function value(): string
    {
        return 'anything';
    }
}
