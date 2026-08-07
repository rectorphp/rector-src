<?php

declare(strict_types=1);

namespace Rector\Tests\Config;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;

final class RectorConfigTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $rectorConfig = $this->getContainer();

        $rectorConfig->configure()
            ->withSets([SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION])
            ->withRules([ReturnTypeFromReturnNewRector::class]);

        // only collect root withRules()
        $this->assertCount(1, SimpleParameterProvider::provideArrayParameter(Option::ROOT_STANDALONE_REGISTERED_RULES));
    }

    public function testRuleWithConfigurationComposerVersionBoundOnSatisfiedConstraint(): void
    {
        $rectorConfig = $this->getContainer();

        $rectorConfig->ruleWithConfigurationComposerVersionBound(
            RenameClassRector::class,
            [
                'SomeOldClass' => 'SomeNewClass',
            ],
            'phpunit/phpunit',
            '>=9.0'
        );

        $registeredRectorRules = SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_RULES);
        $this->assertContains(RenameClassRector::class, $registeredRectorRules);
    }

    public function testRuleWithConfigurationComposerVersionBoundOnUnsatisfiedConstraint(): void
    {
        $rectorConfig = $this->getContainer();

        $rectorConfig->ruleWithConfigurationComposerVersionBound(
            RenameMethodRector::class,
            [new MethodCallRename('SomeClass', 'oldMethod', 'newMethod')],
            'phpunit/phpunit',
            '<9.0'
        );

        $registeredRectorRules = SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_RULES);
        $this->assertNotContains(RenameMethodRector::class, $registeredRectorRules);
    }

    public function testRuleWithConfigurationComposerVersionBoundOnMissingPackage(): void
    {
        $rectorConfig = $this->getContainer();

        $rectorConfig->ruleWithConfigurationComposerVersionBound(
            RenamePropertyRector::class,
            [new RenameProperty('SomeClass', 'oldProperty', 'newProperty')],
            'not-installed/package',
            '>=1.0'
        );

        $registeredRectorRules = SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_RULES);
        $this->assertNotContains(RenamePropertyRector::class, $registeredRectorRules);
    }
}
