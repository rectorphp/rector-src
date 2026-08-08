<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use Rector\Configuration\ConfigurationRuleFilter;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\Configuration;

final class ConfigurationRuleFilterTest extends AbstractLazyTestCase
{
    private ConfigurationRuleFilter $configurationRuleFilter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configurationRuleFilter = $this->make(ConfigurationRuleFilter::class);
    }

    public function testPhpOnlyKeepsMinPhpVersionRules(): void
    {
        $stringableForToStringRector = $this->make(StringableForToStringRector::class);
        $removeDeadInstanceOfRector = $this->make(RemoveDeadInstanceOfRector::class);

        $this->configurationRuleFilter->setConfiguration($this->createConfiguration(true));

        $filteredRectors = $this->configurationRuleFilter->filter(
            [$stringableForToStringRector, $removeDeadInstanceOfRector]
        );

        $this->assertSame([$stringableForToStringRector], $filteredRectors);
    }

    public function testWithoutPhpOnlyKeepsAllRules(): void
    {
        $stringableForToStringRector = $this->make(StringableForToStringRector::class);
        $removeDeadInstanceOfRector = $this->make(RemoveDeadInstanceOfRector::class);

        $this->configurationRuleFilter->setConfiguration($this->createConfiguration(false));

        $filteredRectors = $this->configurationRuleFilter->filter(
            [$stringableForToStringRector, $removeDeadInstanceOfRector]
        );

        $this->assertSame([$stringableForToStringRector, $removeDeadInstanceOfRector], $filteredRectors);
    }

    private function createConfiguration(bool $isPhpOnly): Configuration
    {
        return new Configuration(isPhpOnly: $isPhpOnly);
    }
}
