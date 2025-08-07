<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallArgRector;
use Rector\Removing\ValueObject\RemoveFuncCallArg;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([ArrayFirstLastRector::class]);

    // https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_the_exclude_disabled_parameter_of_get_defined_functions
    $rectorConfig->ruleWithConfiguration(
        RemoveFuncCallArgRector::class,
        [
            new RemoveFuncCallArg('get_defined_functions', 0),
        ],
    );
};
