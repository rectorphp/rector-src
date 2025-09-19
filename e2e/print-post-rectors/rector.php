<?php

use Rector\CodeQuality\Rector\If_\ShortenElseIfRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src/TestClass.php',
    ]);
    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports(true);
    $rectorConfig->rule(ShortenElseIfRector::class);
};
