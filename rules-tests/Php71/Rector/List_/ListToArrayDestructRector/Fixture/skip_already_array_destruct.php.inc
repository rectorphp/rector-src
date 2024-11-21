<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class SkipAlreadyArrayDestruct
{
    public function run($line, $file, $trace)
    {
        [
            'line' => $line,
            'file' => $file,
        ] = $trace;
    }
}
