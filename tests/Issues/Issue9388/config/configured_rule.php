<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Issues\Issue9388\Source\AnnotationToAttribute\ValidateAttributeDecorator;
use Rector\Tests\Issues\Issue9388\Source\Rule\ExtbaseAnnotationToAttributeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    // AttributeDecorator receives every AttributeDecoratorInterface implementation through its
    // "@param AttributeDecoratorInterface[] $decorators" docblock; registering the implementation
    // is enough for the container to discover and inject it by contract
    $rectorConfig->singleton(ValidateAttributeDecorator::class);

    $rectorConfig->importNames(false, false);
    $rectorConfig->phpVersion(PhpVersionFeature::ATTRIBUTES);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'TYPO3\CMS\Extbase\Mvc\Web\Request' => 'TYPO3\CMS\Extbase\Mvc\Request',
    ]);
    $rectorConfig->rule(ExtbaseAnnotationToAttributeRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::ATTRIBUTES);
};
