<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\PartialValueDocblockUpdate\Source\PartialUpdateTestRector;

return RectorConfig::configure()
    ->withRules([PartialUpdateTestRector::class]);
