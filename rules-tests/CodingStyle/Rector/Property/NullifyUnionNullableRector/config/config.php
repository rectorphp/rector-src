<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\VarAnnotationMissingNullableRectorTest;

use Rector\CodingStyle\Rector\Property\NullifyUnionNullableRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(NullifyUnionNullableRector::class);
};
