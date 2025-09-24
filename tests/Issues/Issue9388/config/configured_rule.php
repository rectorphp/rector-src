<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Issues\Issue9388\AnnotationToAttribute\AttributeDecorator;
use Rector\Tests\Issues\Issue9388\AnnotationToAttribute\AttributeDecoratorInterface;
use Rector\Tests\Issues\Issue9388\AnnotationToAttribute\ValidateAttributeDecorator;
use Rector\Tests\Issues\Issue9388\Rule\ExtbaseAnnotationToAttributeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->autotagInterface(AttributeDecoratorInterface::class);
    $rectorConfig->singleton(ValidateAttributeDecorator::class);
    $rectorConfig->when(AttributeDecorator::class)->needs('$decorators')->giveTagged(
        AttributeDecoratorInterface::class
    );

    $rectorConfig->importNames(false, false);
    $rectorConfig->phpVersion(PhpVersionFeature::ATTRIBUTES);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'TYPO3\CMS\Extbase\Mvc\Web\Request' => 'TYPO3\CMS\Extbase\Mvc\Request',
    ]);
    $rectorConfig->rule(ExtbaseAnnotationToAttributeRector::class);
};
