<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Transform\Rector\MethodCall\MethodCallToNewRector;
use Rector\Transform\ValueObject\MethodCallToNew;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(MethodCallToNewRector::class, [
        new MethodCallToNew(
            new ObjectType('Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source\ResponseFactory'),
            'createResponse',
            'Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source\Response',
        ),
    ]);
};
