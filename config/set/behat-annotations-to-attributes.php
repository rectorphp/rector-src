<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('Given', 'Behat\Step\Given', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('When', 'Behat\Step\When', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('Then', 'Behat\Step\Then', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('BeforeSuite', 'Behat\Hook\BeforeSuite', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('AfterSuite', 'Behat\Hook\AfterSuite', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('BeforeFeature', 'Behat\Hook\BeforeFeature', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('AfterFeature', 'Behat\Hook\AfterFeature', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('BeforeScenario', 'Behat\Hook\BeforeScenario', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('AfterScenario', 'Behat\Hook\AfterScenario', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('BeforeStep', 'Behat\Hook\BeforeStep', useValueAsAttributeArgument: true),
        new AnnotationToAttribute('AfterStep', 'Behat\Hook\AfterStep', useValueAsAttributeArgument: true),
    ]);
};
