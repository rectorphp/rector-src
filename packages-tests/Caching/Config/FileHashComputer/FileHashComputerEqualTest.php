<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Config\FileHashComputer;

use Rector\Caching\Config\FileHashComputer;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class FileHashComputerEqualTest extends AbstractLazyTestCase
{
    private FileHashComputer $fileHashComputer;

    protected function setUp(): void
    {
        $this->fileHashComputer = $this->make(FileHashComputer::class);
    }

    public function test(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/FixtureEqual/rector.php']);

        $hashedFile = $this->fileHashComputer->compute(__DIR__ . '/FixtureEqual/rector.php');

        copy(__DIR__ . '/FixtureEqual/rector.php', __DIR__ . '/FixtureEqual/rector_temp_equal.php');
        copy(__DIR__ . '/FixtureEqual/rector_rule_equals.php', __DIR__ . '/FixtureEqual/rector.php');

        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, null);

        $this->bootFromConfigFiles([__DIR__ . '/FixtureEqual/rector.php']);

        $newHashedFile = $this->fileHashComputer->compute(__DIR__ . '/FixtureEqual/rector.php');
        rename(__DIR__ . '/FixtureEqual/rector_temp_equal.php', __DIR__ . '/FixtureEqual/rector.php');

        $this->assertSame($newHashedFile, $hashedFile);
    }
}
