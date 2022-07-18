<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Php;

use Iterator;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class PhpVersionProviderTest extends AbstractTestCase
{
    /**
     * @dataProvider provideValidConfigData()
     */
    public function testValidInput(string $invalidFilePath): void
    {
        $this->bootFromConfigFiles([$invalidFilePath]);

        $phpVersionProvider = $this->getService(PhpVersionProvider::class);
        $phpVersion = $phpVersionProvider->provide();

        $this->assertIsInt($phpVersion);
    }

    /**
     * @return Iterator<string[]>
     */
    public function provideValidConfigData(): Iterator
    {
        yield [__DIR__ . '/config/valid_explicit_value.php'];
    }
}
