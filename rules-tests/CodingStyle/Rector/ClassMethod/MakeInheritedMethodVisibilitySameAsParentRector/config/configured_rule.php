<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([MakeInheritedMethodVisibilitySameAsParentRector::class]);
