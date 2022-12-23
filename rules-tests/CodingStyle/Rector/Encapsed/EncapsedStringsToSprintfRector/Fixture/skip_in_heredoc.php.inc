<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class SkipInHeredoc
{
    public function run(string $data)
    {
        $data = 'test';
        $value = <<<CODE
                            Some {$data}
CODE;

        echo $value;
    }
}
