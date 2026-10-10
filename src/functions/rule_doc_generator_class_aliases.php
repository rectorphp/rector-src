<?php

declare(strict_types=1);

use Rector\Doc\AbstractCodeSample;
use Rector\Doc\CodeSample\CodeSample;
use Rector\Doc\CodeSample\ComposerJsonAwareCodeSample;
use Rector\Doc\CodeSample\ConfiguredCodeSample;
use Rector\Doc\CodeSample\ExtraFileCodeSample;
use Rector\Doc\RuleDefinition;
use Rector\RuleDoc\Contract\Category\CategoryInfererInterface;
use Rector\RuleDoc\Contract\CodeSampleInterface;
use Rector\RuleDoc\Contract\ConfigurableRuleInterface;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\Contract\RuleCodeSamplePrinterInterface;
use Rector\RuleDoc\Exception\PoorDocumentationException;
use Rector\RuleDoc\Exception\ShouldNotHappenException;

// backward-compatibility aliases for the former symplify/rule-doc-generator-contracts package,
// so existing extension and 3rd party rules referencing the Symplify\RuleDocGenerator namespace keep working
$ruleDocGeneratorClassAliases = [
    DocumentedRuleInterface::class => 'Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface',
    ConfigurableRuleInterface::class => 'Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface',
    CodeSampleInterface::class => 'Symplify\RuleDocGenerator\Contract\CodeSampleInterface',
    RuleCodeSamplePrinterInterface::class => 'Symplify\RuleDocGenerator\Contract\RuleCodeSamplePrinterInterface',
    CategoryInfererInterface::class => 'Symplify\RuleDocGenerator\Contract\Category\CategoryInfererInterface',
    RuleDefinition::class => 'Symplify\RuleDocGenerator\ValueObject\RuleDefinition',
    AbstractCodeSample::class => 'Symplify\RuleDocGenerator\ValueObject\AbstractCodeSample',
    CodeSample::class => 'Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample',
    ConfiguredCodeSample::class => 'Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample',
    ComposerJsonAwareCodeSample::class => 'Symplify\RuleDocGenerator\ValueObject\CodeSample\ComposerJsonAwareCodeSample',
    ExtraFileCodeSample::class => 'Symplify\RuleDocGenerator\ValueObject\CodeSample\ExtraFileCodeSample',
    PoorDocumentationException::class => 'Symplify\RuleDocGenerator\Exception\PoorDocumentationException',
    ShouldNotHappenException::class => 'Symplify\RuleDocGenerator\Exception\ShouldNotHappenException',
];

foreach ($ruleDocGeneratorClassAliases as $currentClass => $legacyClass) {
    class_alias($currentClass, $legacyClass);
}
