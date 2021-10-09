<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Php;

use Rector\Core\Exception\Configuration\InvalidConfigurationException;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PhpVersionProviderTest extends AbstractTestCase
{
    private PhpVersionProvider $phpVersionProvider;

    protected function setUp(): void
    {
        $this->boot();
        $this->phpVersionProvider = $this->getService(PhpVersionProvider::class);
    }

    public function test(): void
    {
        $phpVersion = $this->phpVersionProvider->provide();
        $this->assertSame(100000, $phpVersion);
    }

    public function testInvalidInput()
    {
        $this->expectException(InvalidConfigurationException::class);

        $invalidConfigFileInfo = new SmartFileInfo(__DIR__ . '/config/invalid_input.php');
        $this->bootFromConfigFileInfos([$invalidConfigFileInfo]);

        $phpVersionProvider = $this->getService(PhpVersionProvider::class);
        $phpVersionProvider->provide();
    }
}
