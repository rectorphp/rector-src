<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\StringClassNameConstantDefaultValue\Source\ChangeStringClassNameToOtherStringClassNameRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        StringClassNameToClassConstantRector::class,
        AddDefaultValueForUndefinedVariableRector::class,
        ChangeStringClassNameToOtherStringClassNameRector::class,
    ]);
};
