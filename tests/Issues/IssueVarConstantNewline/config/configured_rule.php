<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(VarConstantCommentRector::class);
    $services->set(NewlineAfterStatementRector::class);
};
