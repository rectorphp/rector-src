<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ParamTypeFromStrictTypedPropertyRector::class);
};
