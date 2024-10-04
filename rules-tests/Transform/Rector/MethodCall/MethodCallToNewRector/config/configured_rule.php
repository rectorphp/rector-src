<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\MethodCall\MethodCallToNewRector;
use PHPstan\Type\ObjectType;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(MethodCallToNewRector::class, [
        new \Rector\Transform\ValueObject\MethodCallToNew(
            new ObjectType('Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source\ResponseFactory'),
            'createResponse',
            'Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source\Response',
        ),
    ]);
};
