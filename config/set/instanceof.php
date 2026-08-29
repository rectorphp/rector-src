<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

// note: all instanceof rules were moved to code quality and type declaration sets
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([]);
};
