<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Source\ClassThatWillChangeReturnType;
use Rector\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector;
use Rector\Transform\ValueObject\ClassMethodReference;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::RETURN_TYPE_WILL_CHANGE_ATTRIBUTE);

    $rectorConfig->ruleWithConfiguration(ReturnTypeWillChangeRector::class, [
        new ClassMethodReference(ClassThatWillChangeReturnType::class, 'changeMyReturn'),
    ]);
};
