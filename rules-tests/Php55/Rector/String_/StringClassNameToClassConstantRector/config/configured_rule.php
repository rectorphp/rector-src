<?php

declare(strict_types=1);

use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(StringClassNameToClassConstantRector::class)
        ->configure(['Nette\*', 'Error', 'Exception']);
};
