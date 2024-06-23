<?php

declare(strict_types=1);

use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Transform\Rector\Attribute\ReplaceAttributeUseWithAnotherAttributeRector;
use Rector\Transform\ValueObject\ReplaceAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(ReplaceAttributeUseWithAnotherAttributeRector::class, [
            new ReplaceAttribute(new FullyQualified('Foobar'), new FullyQualified('Barfoo')),
        ]);
};
