<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Rector\Property\DoctrineTargetEntityStringToClassConstantRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DoctrineTargetEntityStringToClassConstantRector::class);
};
