<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\VarAnnotationMissingNullableRectorTest;

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Property\NullifyUnionNullableRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(NullifyUnionNullableRector::class);
};
