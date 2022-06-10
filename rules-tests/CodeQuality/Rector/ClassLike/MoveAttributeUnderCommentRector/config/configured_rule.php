<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassLike\MoveAttributeUnderCommentRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MoveAttributeUnderCommentRector::class);
};
