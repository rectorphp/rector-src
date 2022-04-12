<?php

declare(strict_types=1);

use Rector\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(WrapVariableVariableNameInCurlyBracesRector::class);
};
