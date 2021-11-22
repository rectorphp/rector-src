<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class SkipWithEncapsedStringPart2
{
    public function run(int $id): array
    {
        $data                = [
            'url' => "/$id.pdf",
        ];

        return $data;
    }
}
