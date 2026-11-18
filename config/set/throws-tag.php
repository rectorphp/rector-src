<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector;

/**
 * Opt-in: the unchecked exceptions are project specific, so the rule has no default list.
 * Enable it with ->withPreparedSets(throwsTag: true) and add yours with
 * ->withConfiguredRule(AddThrowsTagRector::class, [LogicException::class]).
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([AddThrowsTagRector::class]);
};
