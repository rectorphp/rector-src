<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::STRING_IN_ASSERT_ARG);
    $rectorConfig->rule(InlineVarDocTagToAssertRector::class);
};
