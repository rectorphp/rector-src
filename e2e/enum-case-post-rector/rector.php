<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src/Module1',
    ]);

    $rectorConfig->rule(\Rector\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector::class);
};
