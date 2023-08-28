<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(\Rector\Php55\Rector\String_\StringClassNameToClassConstantRector::class);

    $rectorConfig->ruleWithConfiguration(
        \Rector\Renaming\Rector\String_\RenameStringRector::class,
        [
    		'Rector\Core\Tests\Issues\DoubleRun\Fixture\RenameString' => 'new test',
        ]
    );
};
