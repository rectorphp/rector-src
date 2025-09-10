<?php

declare(strict_types=1);

use Rector\Assert\Enum\AssertClassName;
use Rector\Assert\Rector\ClassMethod\AddAssertArrayFromClassMethodDocblockRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddAssertArrayFromClassMethodDocblockRector::class, [
        AssertClassName::BEBERLEI,
    ]);
};
