<?php

declare(strict_types=1);

namespace Rector\Tests\Bootstrap;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Bootstrap\CliOptionValueResolver;

final class CliOptionValueResolverTest extends TestCase
{
    /**
     * @param array<int, mixed> $argv
     */
    #[DataProvider('provideData')]
    public function test(array $argv, ?string $expectedValue): void
    {
        $this->assertSame($expectedValue, CliOptionValueResolver::resolve($argv, ['--config', '-c']));
    }

    /**
     * @return iterable<string, array{array<int, mixed>, string|null}>
     */
    public static function provideData(): iterable
    {
        yield 'long with space' => [['bin/rector', 'process', '--config', 'rector.php'], 'rector.php'];
        yield 'long with equals' => [['bin/rector', 'process', '--config=rector.php'], 'rector.php'];
        yield 'short with space' => [['bin/rector', 'process', '-c', 'rector.php'], 'rector.php'];
        yield 'short with equals' => [['bin/rector', 'process', '-c=rector.php'], 'rector.php'];
        yield 'first occurrence wins' => [['bin/rector', '-c', 'first.php', '--config', 'second.php'], 'first.php'];
        yield 'missing value' => [['bin/rector', 'process', '--config'], null];
        yield 'missing option' => [['bin/rector', 'process', '--dry-run'], null];
        yield 'similar prefix is not matched' => [['bin/rector', 'process', '--configuration', 'rector.php'], null];
        yield 'after end of options' => [['bin/rector', 'process', '--', '--config', 'rector.php'], null];
    }
}
