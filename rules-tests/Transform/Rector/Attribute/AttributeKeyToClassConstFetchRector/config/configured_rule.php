<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector;
use Rector\Transform\ValueObject\AttributeKeyToClassConstFetch;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AttributeKeyToClassConstFetchRector::class, [
            new AttributeKeyToClassConstFetch('Doctrine\ORM\Mapping\Column', 'type', 'Doctrine\DBAL\Types\Types', [
                'string' => 'STRING',
            ]),
            new AttributeKeyToClassConstFetch('Rector\Tests\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector\Source\TestAttribute', 'type', 'Rector\Tests\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector\Source\Constant', [
                'value' => 'VALUE',
            ]),
        ]);
};
