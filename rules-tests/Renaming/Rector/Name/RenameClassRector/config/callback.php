<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\EnforceExceptionSuffixCallback;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\EnforceInterfaceSuffixCallback;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            '__callbacks__' => [new EnforceExceptionSuffixCallback(), new EnforceInterfaceSuffixCallback()],
        ]);
};
