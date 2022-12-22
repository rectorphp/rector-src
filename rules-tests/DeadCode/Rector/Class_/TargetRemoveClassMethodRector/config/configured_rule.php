<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Class_\TargetRemoveClassMethodRector;
use Rector\DeadCode\ValueObject\TargetRemoveClassMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(TargetRemoveClassMethodRector::class, [
        new TargetRemoveClassMethod(
            'Rector\Tests\DeadCode\Rector\Class_\TargetRemoveClassMethodRector\Fixture\SomeClass',
            'removeMe'
        ),
    ]);
};
