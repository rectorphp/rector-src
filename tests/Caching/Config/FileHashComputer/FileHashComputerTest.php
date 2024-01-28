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
        $this->fileHashComputer = $this->make(FileHashComputer::class);
    }

    public function testRectorPhpChanged(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, null);

        $this->bootFromConfigFiles([__DIR__ . '/Fixture/rector.php']);

        $hashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');

        rename(__DIR__ . '/Fixture/rector.php', __DIR__ . '/Fixture/rector_temp.php');
        rename(__DIR__ . '/Fixture/updated_rector_rule.php', __DIR__ . '/Fixture/rector.php');

        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, null);

        $this->bootFromConfigFiles([__DIR__ . '/Fixture/rector.php']);

        $newHashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');
        rename(__DIR__ . '/Fixture/rector_temp.php', __DIR__ . '/Fixture/rector.php');

        $this->assertNotSame($newHashedFile, $hashedFile);
    }

    public function testRectorPhpNotChanged(): void
    {
        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, null);

        $this->bootFromConfigFiles([__DIR__ . '/Fixture/rector.php']);

        $hashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');

        rename(__DIR__ . '/Fixture/rector.php', __DIR__ . '/Fixture/rector_temp_equal.php');
        rename(__DIR__ . '/Fixture/rector_rule_equals.php', __DIR__ . '/Fixture/rector.php');

        SimpleParameterProvider::setParameter(Option::REGISTERED_RECTOR_RULES, null);

        $this->bootFromConfigFiles([__DIR__ . '/Fixture/rector.php']);

        $newHashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');
        rename(__DIR__ . '/Fixture/rector_temp_equal.php', __DIR__ . '/Fixture/rector.php');

        $this->assertSame($newHashedFile, $hashedFile);
    }
}
