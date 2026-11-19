<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ParamTagDescriptionToParamDocCommentRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_86);
};
