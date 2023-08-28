<?php

declare(strict_types=1);
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Renaming\Rector\String_\RenameStringRector;

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(StringClassNameToClassConstantRector::class);

    $rectorConfig->ruleWithConfiguration(
        RenameStringRector::class,
        [
    		'Rector\Core\Tests\Issues\DoubleRun\Fixture\RenameString' => 'new test',
        ]
    );
};
