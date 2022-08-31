<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Skipper\Skipper\Skip\Source\AnotherClassToSkip;
use Rector\Tests\Skipper\Skipper\Skip\Source\SomeClassToSkip;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->skip([
        // classes
        SomeClassToSkip::class,

        // classes only in specific paths
        AnotherClassToSkip::class => ['Fixture/someFile', '*/someDirectory/*'],

        // file paths
        __DIR__ . '/../Fixture/AlwaysSkippedPath',
        '*\PathSkippedWithMask\*',
    ]);
};
