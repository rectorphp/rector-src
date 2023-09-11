<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\PartialValueDocblockUpdate\Source\PartialUpdateTestRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(PartialUpdateTestRector::class);
};
