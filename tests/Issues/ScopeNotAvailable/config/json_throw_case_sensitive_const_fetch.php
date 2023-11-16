<?php

declare(strict_types=1);

use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        JsonThrowOnErrorRector::class,
        SensitiveConstantNameRector::class
    ]);
};
