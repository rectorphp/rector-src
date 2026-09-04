<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use Rector\Configuration\ConfigurationFactory;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Console\ProcessConfigureDecorator;
use Rector\FileSystem\FilesFinder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;

final class ConfigurationFactoryTest extends AbstractLazyTestCase
{
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

        $this->make(ConfigurationFactory::class)
            ->createFromInput($this->createInput([__DIR__ . '/Source']));

        $this->assertSame(
            [__DIR__ . '/Source'],
            SimpleParameterProvider::provideArrayParameter(Option::SOURCE)
        );
    }

    public function testPublishesConfiguredPathsAsSourceParameterWhenNoneGivenOnTheCommandLine(): void
    {
        SimpleParameterProvider::setParameter(Option::PATHS, [__DIR__ . '/config']);

        $this->make(ConfigurationFactory::class)
            ->createFromInput($this->createInput([]));

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
    private function createInput(array $paths): ArrayInput
    {
        $command = new Command('process');
        ProcessConfigureDecorator::decorate($command);

        return new ArrayInput([
            Option::SOURCE => $paths,
        ], $command->getDefinition());
    }
}
