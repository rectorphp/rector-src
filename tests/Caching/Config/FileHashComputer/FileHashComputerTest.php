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

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileHashComputer = $this->make(FileHashComputer::class);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, null);
        SimpleParameterProvider::setParameter(Option::AUTO_IMPORT_NAMES, null);
    }

    public function testOutputAffectingParameterChangesHash(): void
    {
        $configFilePath = __DIR__ . '/Fixture/rector.php';

        SimpleParameterProvider::setParameter(Option::AUTO_IMPORT_NAMES, null);
        $hashBefore = $this->fileHashComputer->compute($configFilePath);

        SimpleParameterProvider::setParameter(Option::AUTO_IMPORT_NAMES, true);
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
}
