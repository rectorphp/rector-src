<?php

namespace Rector\Tests\Php74\Rector\StaticCall\ExportToReflectionFunctionRector\Fixture;

class SomeExporter
{
    public static function export($name)
    {
    }
}

class SkipOtherClass
{
    public function run()
    {
        $value = SomeExporter::export('foo');
    }
}
