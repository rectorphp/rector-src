<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\ClassMethod\FinalPropertyPromotionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FinalPropertyPromotionRector::class);

};
