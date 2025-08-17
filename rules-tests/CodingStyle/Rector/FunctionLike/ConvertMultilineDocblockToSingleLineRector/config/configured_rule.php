<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FunctionLike\ConvertMultilineDocblockToSingleLineRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ConvertMultilineDocblockToSingleLineRector::class]);
