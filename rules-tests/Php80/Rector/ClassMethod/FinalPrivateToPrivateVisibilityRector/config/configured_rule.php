<?php

declare(strict_types=1);

use Rector\Php80\Rector\ClassMethod\FinalPrivateToPrivateVisibilityRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(FinalPrivateToPrivateVisibilityRector::class);
};
