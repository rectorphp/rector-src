<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Php;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class PhpVersionProviderTest extends AbstractTestCase
{
    #[DataProvider('provideValidConfigData')]
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
    public static function provideValidConfigData(): Iterator
    {
        yield [__DIR__ . '/config/valid_explicit_value.php'];
    }
}
