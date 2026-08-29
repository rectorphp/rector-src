<?php

declare(strict_types=1);

namespace Rector\Tests\Config;

use Rector\Config\RectorConfig;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\Rector\RectorInterface;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Config\Source\DependencyOnlyRector;
use Rector\Tests\Config\Source\RegisteringRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;

final class RectorConfigTest extends AbstractLazyTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // these tests assert on root rule registration, which is decided by a
        // static "first configure() in the process is root" flag; reset it and
        // the registered-rule lists so the assertions hold whether this class
        // runs alone or batched into one warm process by a parallel runner
        RectorConfig::resetRecreated();
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, []);
        SimpleParameterProvider::setParameter(Option::ROOT_STANDALONE_REGISTERED_RULES, []);
    }

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

    public function testRuleTakenOnlyAsDependencyIsNotActive(): void
    {
        $rectorConfig = $this->getContainer();

        $rectorConfig->rule(RegisteringRector::class);

        // building the registered rule also builds and caches its constructor dependency,
        // which is a rule too - but one no config asked for
        $activeRectorClasses = array_map(
            static fn (object $rector): string => $rector::class,
            $rectorConfig->findByContract(RectorInterface::class)
        );

        $this->assertContains(RegisteringRector::class, $activeRectorClasses);
        $this->assertNotContains(DependencyOnlyRector::class, $activeRectorClasses);
    }
}
