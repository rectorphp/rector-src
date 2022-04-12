<?php

declare(strict_types=1);

use Rector\DowngradePhp73\Rector\String_\DowngradeFlexibleHeredocSyntaxRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeFlexibleHeredocSyntaxRector::class);
};
