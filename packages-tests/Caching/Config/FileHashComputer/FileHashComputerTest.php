<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Config\FileHashComputer;

use Nette\Utils\FileSystem;
use Rector\Caching\Config\FileHashComputer;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class FileHashComputerTest extends AbstractLazyTestCase
{
    private FileHashComputer $fileHashComputer;

    protected function setUp(): void
    {
        $this->fileHashComputer = $this->make(FileHashComputer::class);
    }

    public function testRectorPhpChanged(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/Fixture/rector.php']);

        $oldRectorConfig = FileSystem::read(__DIR__ . '/Fixture/rector.php');
        $hashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');

        copy(__DIR__ . '/Fixture/rector.php', __DIR__ . '/Fixture/rector_temp.php');
        copy(__DIR__ . '/Fixture/updated_rector_rule.php', __DIR__ . '/Fixture/rector.php');
        $newRectorConfig = FileSystem::read(__DIR__ . '/Fixture/rector.php');

        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, null);

        $this->bootFromConfigFiles([__DIR__ . '/Fixture/rector.php']);

        $newHashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');
        rename(__DIR__ . '/Fixture/rector_temp.php', __DIR__ . '/Fixture/rector.php');

        $this->assertNotSame($oldRectorConfig, $newRectorConfig);
        $this->assertNotSame($newHashedFile, $hashedFile);
    }

    public function testRectorPhpNotChanged(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/Fixture/rector.php']);

        $oldRectorConfig = FileSystem::read(__DIR__ . '/Fixture/rector.php');
        $hashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');

        copy(__DIR__ . '/Fixture/rector.php', __DIR__ . '/Fixture/rector_temp.php');
        copy(__DIR__ . '/Fixture/rector_rule_equals.php', __DIR__ . '/Fixture/rector.php');

        $newRectorConfig = FileSystem::read(__DIR__ . '/Fixture/rector.php');

        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, null);

        $this->bootFromConfigFiles([__DIR__ . '/Fixture/rector.php']);

        $newHashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');
        rename(__DIR__ . '/Fixture/rector_temp.php', __DIR__ . '/Fixture/rector.php');

        $this->assertSame($oldRectorConfig, $newRectorConfig);
        $this->assertSame($newHashedFile, $hashedFile);
    }
}
