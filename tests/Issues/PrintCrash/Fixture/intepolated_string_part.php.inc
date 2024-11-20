<?php

namespace Rector\Tests\Issues\IndentationCrash\PrintCrash;

final class InterpolatedStringPart
{
    public function test(array $checkInputs): void
    {
        foreach ($checkInputs as $index => $checkInput) {
            echo "Input#{$index}: Wrong tokenTime";
        }
    }
}

