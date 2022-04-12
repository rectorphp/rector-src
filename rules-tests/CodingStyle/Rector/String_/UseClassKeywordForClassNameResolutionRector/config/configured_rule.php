<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(UseClassKeywordForClassNameResolutionRector::class);
};
