<?php

declare(strict_types=1);

use PhpParser\Node\Expr\Cast\Double;
use PhpParser\Node\Expr\Cast\Int_;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Cast\RenameCastRector;
use Rector\Renaming\ValueObject\RenameCast;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameCastRector::class, [
            new RenameCast(Double::class, Double::KIND_REAL, Double::KIND_FLOAT),
            new RenameCast(Double::class, Double::KIND_DOUBLE, Double::KIND_FLOAT),
            new RenameCast(Int_::class, Int_::KIND_INTEGER, Int_::KIND_INT),
        ]);
};
