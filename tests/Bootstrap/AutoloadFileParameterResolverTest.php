<?php

declare(strict_types=1);

namespace Rector\Tests\Bootstrap;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Bootstrap\AutoloadFileParameterResolver;
use Rector\Caching\Config\FileHashComputer;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;

final class AutoloadFileParameterResolverTest extends TestCase
{
    protected function setUp(): void
    {
        SimpleParameterProvider::setParameter(Option::AUTOLOAD_FILE, '');
    }

    /**
     * all spellings must resolve to the same value, or the main process and
     * parallel workers, which receive the space-separated form, would compute
     * different configuration hashes
     *
     * @param array<int, string> $argv
     */
    #[DataProvider('provideArgvSpellings')]
    public function testEverySpellingResolvesToSameRealPath(array $argv): void
    {
        AutoloadFileParameterResolver::resolveFromArgv($argv);

        $this->assertSame(
            (string) realpath(__FILE__),
            SimpleParameterProvider::provideStringParameter(Option::AUTOLOAD_FILE)
        );
    }

    /**
     * @return iterable<string, array{array<int, string>}>
     */
    public static function provideArgvSpellings(): iterable
    {
        $relativePath = 'tests/Bootstrap/' . basename(__FILE__);

        yield 'long with space' => [['bin/rector', 'process', '--autoload-file', __FILE__]];
        yield 'long with equals' => [['bin/rector', 'process', '--autoload-file=' . __FILE__]];
        yield 'short with space' => [['bin/rector', 'process', '-a', __FILE__]];
        yield 'relative path is normalized' => [['bin/rector', 'process', '-a', $relativePath]];
    }

    public function testWithoutOptionParameterStaysUntouched(): void
    {
        AutoloadFileParameterResolver::resolveFromArgv(['bin/rector', 'process', '--dry-run']);

        $this->assertSame('', SimpleParameterProvider::provideStringParameter(Option::AUTOLOAD_FILE));
    }

    public function testResolvedAutoloadFileChangesConfigurationHash(): void
    {
        $fileHashComputer = new FileHashComputer();
        $configFilePath = __DIR__ . '/config/some_config.php';

        $hashWithout = $fileHashComputer->compute($configFilePath);

        AutoloadFileParameterResolver::resolveFromArgv(['bin/rector', 'process', '-a', __FILE__]);

        $this->assertNotSame($hashWithout, $fileHashComputer->compute($configFilePath));
    }
}
