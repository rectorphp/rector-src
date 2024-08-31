<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\ReplacePropertyObjectTypeHintRector;
use Rector\TypeDeclaration\ValueObject\ReplaceObjectTypeHint;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(ReplacePropertyObjectTypeHintRector::class, [
            new ReplaceObjectTypeHint(new ObjectType('Carbon\Carbon'), new ObjectType('Carbon\CarbonInterface')),
        ]);
};
