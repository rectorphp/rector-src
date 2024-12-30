<?php

namespace Rector\Tests\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector\Fixture;

final class SkipFopenOrDieInAssign
{
    public function run(string $fileLocation)
    {
        $file = fopen($fileLocation, 'w') or die('Unable to open file!');
        fwrite($file, "John Doe\nJane Doe\n");
        fclose($file);
    }
}
