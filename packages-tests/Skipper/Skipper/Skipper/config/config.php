<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Skipper\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\Tests\Skipper\Skipper\Skipper\Fixture\Element\SixthSense;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->skip([
        // windows like path
        '*\SomeSkipped\*',

        __DIR__ . '/../Fixture/SomeSkippedPath',
        __DIR__ . '/../Fixture/SomeSkippedPathToFile/any.txt',

        // elements
        FifthElement::class,
        SixthSense::class,
    ]);
};
