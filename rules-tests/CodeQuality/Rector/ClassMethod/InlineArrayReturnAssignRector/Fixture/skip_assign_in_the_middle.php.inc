<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipAssignInTheMiddle
{
    public function run(array $resul)
    {
        $items = [];

        $result = 'Timmy';
        $items[] = $this->resolve($result);

        return $items;
    }

    private function resolve(string $result)
    {
        return $result;
    }
}
