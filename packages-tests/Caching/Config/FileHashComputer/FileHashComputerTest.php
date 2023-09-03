<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\Config\FileHashComputer;

use PHPUnit\Framework\TestCase;
use Rector\Caching\Config\FileHashComputer;

class FileHashComputerTest extends TestCase
{
    private FileHashComputer $fileHashComputer;

    protected function setUp(): void
    {
        $this->fileHashComputer = new FileHashComputer();
    }

    public function testRectorPhpChanged()
    {
        $hashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');

        copy(__DIR__ . '/Fixture/rector.php', __DIR__ . '/Fixture/rector_temp.php');
        copy(__DIR__ . '/Fixture/updated_rector_rule.php', __DIR__ . '/Fixture/rector.php');

        $newHashedFile = $this->fileHashComputer->compute(__DIR__ . '/Fixture/rector.php');

        $this->assertNotSame($newHashedFile, $hashedFile);

        rename(__DIR__ . '/Fixture/rector_temp.php', __DIR__ . '/Fixture/rector.php');
    }
}