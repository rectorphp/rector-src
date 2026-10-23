<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Configuration\ConfigurationFactory;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\FileSystem\FilesFinder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\Configuration;

final class ConfigurationFactoryTest extends AbstractLazyTestCase
{
    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::IS_RUN_NARROWED, false);
        SimpleParameterProvider::setParameter(Option::SOURCE, []);
        SimpleParameterProvider::setParameter(Option::PATHS, []);
    }

    public function test(): void
    {
        $configurationFactory = $this->make(ConfigurationFactory::class);
        $configuration = $configurationFactory->createForTests([
            __DIR__ . '/../../tests-paths/path/*/some_directory/*',
            __DIR__ . '/../../tests-paths/path/NoExtensionFile',
        ]);

        $filesFinder = $this->make(FilesFinder::class);

        $filePaths = $filesFinder->findInDirectoriesAndFiles($configuration->getPaths());
        $this->assertCount(3, $filePaths);

        $firstFilePath = $filePaths[0];
        $secondFilePath = $filePaths[1];
        $thirdFilePath = $filePaths[2];

        $this->assertSame(
            realpath(__DIR__ . '/../../tests-paths/path/wildcard-nested/some_directory/AnotherFile.php'),
            realpath($firstFilePath)
        );

        $this->assertSame(
            realpath(__DIR__ . '/../../tests-paths/path/wildcard-next/some_directory/YetAnotherFile.php'),
            realpath($secondFilePath),
        );

        $this->assertSame(
            realpath(__DIR__ . '/../../tests-paths/path/NoExtensionFile'),
            realpath($thirdFilePath),
        );
    }

    public function testPublishesCommandLinePathsAsSourceParameter(): void
    {
        SimpleParameterProvider::setParameter(Option::PATHS, [__DIR__ . '/config']);

        $this->createConfiguration([__DIR__ . '/Source']);

        $this->assertSame(
            [__DIR__ . '/Source'],
            SimpleParameterProvider::provideArrayParameter(Option::SOURCE)
        );
    }

    public function testPublishesConfiguredPathsAsSourceParameterWhenNoneGivenOnTheCommandLine(): void
    {
        SimpleParameterProvider::setParameter(Option::PATHS, [__DIR__ . '/config']);

        $this->createConfiguration([]);

        $this->assertSame(
            [__DIR__ . '/config'],
            SimpleParameterProvider::provideArrayParameter(Option::SOURCE)
        );
    }

    public function testSourceParameterIsOutsideTheCacheInvalidationHash(): void
    {
        // The processed paths change per invocation. Were they hashed, running Rector on a
        // single file and then on the whole project would drop the cache each time.
        SimpleParameterProvider::setParameter(Option::SOURCE, ['src']);
        $hashForOnePath = SimpleParameterProvider::hashForCacheInvalidation();

        SimpleParameterProvider::setParameter(Option::SOURCE, ['tests', 'src/Some/Other.php']);

        $this->assertSame($hashForOnePath, SimpleParameterProvider::hashForCacheInvalidation());
    }

    /**
     * @param string[] $paths
     */
    private function createConfiguration(array $paths): Configuration
    {
        return $this->make(ConfigurationFactory::class)
            ->create(
                $paths,
                false,
                false,
                false,
                false,
                ConsoleOutputFormatter::NAME,
                [],
                false,
                false,
                null,
                null,
                false,
                null,
                '',
                '',
                null,
                false,
            );
    }
}
