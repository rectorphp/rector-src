<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Php;

use Rector\Core\Exception\Configuration\InvalidConfigurationException;
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

    /**
     * @dataProvider provideInvalidConfigData()
     */
    public function testInvalidInput(SmartFileInfo $invalidFileInfo): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->bootFromConfigFileInfos([$invalidFileInfo]);

        $phpVersionProvider = $this->getService(PhpVersionProvider::class);
        $phpVersionProvider->provide();
    }

    public function provideInvalidConfigData(): \Iterator
    {
        yield [new SmartFileInfo(__DIR__ . '/config/invalid_input.php')];
        yield [new SmartFileInfo(__DIR__ . '/config/invalid_number_input.php')];
    }
}
