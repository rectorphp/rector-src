<?php

declare(strict_types=1);

use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AddLiteralSeparatorToNumberRector::class)
        ->configure([
            AddLiteralSeparatorToNumberRector::LIMIT_VALUE => 1_000_000,
        ]);
};
