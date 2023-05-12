<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\OrderArrayParamRector;
use Rector\CodingStyle\ValueObject\OrderArray\OrderArrayParam;
use Rector\Config\RectorConfig;
use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Source\Groups;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(OrderArrayParamRector::class, [
            new OrderArrayParam([
                Groups::class => OrderArrayParamRector::ASC,
            ]),
        ]);
};
