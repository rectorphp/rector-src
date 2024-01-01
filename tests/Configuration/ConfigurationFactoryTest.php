<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use Rector\Configuration\ConfigurationFactory;
use Rector\FileSystem\FilesFinder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ConfigurationFactoryTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $configurationFactory = $this->make(ConfigurationFactory::class);
        $configuration = $configurationFactory->createForTests([
            __DIR__ . '/../../tests-paths/path/*/some_directory/*',
        ]);

        $filesFinder = $this->make(FilesFinder::class);

        $filePaths = $filesFinder->findInDirectoriesAndFiles($configuration->getPaths());
        $this->assertCount(2, $filePaths);

        $firstFilePath = $filePaths[0];
        $secondFilePath = $filePaths[1];

        $this->assertSame(
            realpath(__DIR__ . '/../../tests-paths/path/wildcard-nested/some_directory/AnotherFile.php'),
            realpath($firstFilePath)
        );

        $this->assertSame(
            realpath(__DIR__ . '/../../tests-paths/path/wildcard-next/some_directory/YetAnotherFile.php'),
            realpath($secondFilePath),
        );
    }
}
