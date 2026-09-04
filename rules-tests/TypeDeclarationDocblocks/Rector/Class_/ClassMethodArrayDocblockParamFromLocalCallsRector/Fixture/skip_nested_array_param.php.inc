<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipNestedArrayParam
{
    public function go(): void
    {
        $this->run([['name' => 'John', 'age' => 30]]);
    }

    private function run(array $rows): void
    {
    }
}
