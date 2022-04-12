<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnTypeFromStrictTypedPropertyRector::class);
};
