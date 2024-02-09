<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector;

return RectorConfig::configure()->withRules([RenameVariableToMatchNewTypeRector::class]);
