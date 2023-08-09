<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php74\Rector\ArrayDimFetch\CurlyToSquareBracketArrayStringRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::DEPRECATE_CURLY_BRACKET_ARRAY_STRING);

    $rectorConfig->rule(CurlyToSquareBracketArrayStringRector::class);
};
