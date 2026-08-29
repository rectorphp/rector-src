<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Config\FileHashComputer;

use Rector\Caching\Config\FileHashComputer;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class FileHashComputerTest extends AbstractLazyTestCase
{
    private FileHashComputer $fileHashComputer;

    /**
     * @var mixed[]
     */
    private array $originalRules = [];

    /**
     * @var mixed[]
     */
    private array $originalFileExtensions = [];

    /**
     * @var mixed[]
     */
    private array $originalRuleConfigurations = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileHashComputer = $this->make(FileHashComputer::class);

        // the parameter bag is a global static shared across the whole test process, restore it after
        $this->originalRules = SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_RULES);
        $this->originalFileExtensions = SimpleParameterProvider::provideArrayParameter(Option::FILE_EXTENSIONS);
        $this->originalRuleConfigurations = SimpleParameterProvider::provideArrayParameter(Option::RULE_CONFIGURATIONS);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, $this->originalRules);
        SimpleParameterProvider::setParameter(Option::FILE_EXTENSIONS, $this->originalFileExtensions);
        SimpleParameterProvider::setParameter(Option::RULE_CONFIGURATIONS, $this->originalRuleConfigurations);
    }

    public function testOutputAffectingParameterChangesHash(): void
    {
        $configFilePath = __DIR__ . '/Fixture/rector.php';

        SimpleParameterProvider::setParameter(Option::FILE_EXTENSIONS, ['php']);
        $hashBefore = $this->fileHashComputer->compute($configFilePath);

        SimpleParameterProvider::setParameter(Option::FILE_EXTENSIONS, ['php', 'phtml']);
        $hashAfter = $this->fileHashComputer->compute($configFilePath);

        $this->assertNotSame($hashBefore, $hashAfter);
    }

    public function testRuleChangeIsExcludedFromHash(): void
    {
        $configFilePath = __DIR__ . '/Fixture/rector.php';

        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, ['Rector\\SomeRule']);
        $hashBefore = $this->fileHashComputer->compute($configFilePath);

        // registered rules are compared directionally, not by the strict hash
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, ['Rector\\SomeRule', 'Rector\\OtherRule']);
        $hashAfter = $this->fileHashComputer->compute($configFilePath);

        $this->assertSame($hashBefore, $hashAfter);
    }

    public function testConfiguredRuleValueChangeChangesHash(): void
    {
        $configFilePath = __DIR__ . '/Fixture/rector.php';

        SimpleParameterProvider::setParameter(Option::RULE_CONFIGURATIONS, [
            'Rector\\SomeRule' => ['old value'],
        ]);
        $hashBefore = $this->fileHashComputer->compute($configFilePath);

        // same rule, changed configuration value must invalidate the cache
        SimpleParameterProvider::setParameter(Option::RULE_CONFIGURATIONS, [
            'Rector\\SomeRule' => ['new value'],
        ]);
        $hashAfter = $this->fileHashComputer->compute($configFilePath);

        $this->assertNotSame($hashBefore, $hashAfter);
    }
}
