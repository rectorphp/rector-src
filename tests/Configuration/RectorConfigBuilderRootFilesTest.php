<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\TestCase;
use Rector\Config\RectorConfig;
use Rector\Configuration\RectorConfigBuilder;
use ReflectionProperty;
use Webmozart\Assert\Assert;

final class RectorConfigBuilderRootFilesTest extends TestCase
{
    /**
     * Deliberately not in name order: the filesystem returns entries in an order of its own,
     * which differs between machines, e.g. between two CI runner images.
     *
     * @var string[]
     */
    private const array ROOT_FILE_NAMES = ['rector.php', 'ecs.php', '.php-cs-fixer.dist.php', 'deploy.php'];

    private string $originalWorkingDirectory;

    private string $projectDirectory;

    protected function setUp(): void
    {
        $workingDirectory = getcwd();
        Assert::string($workingDirectory);
        $this->originalWorkingDirectory = $workingDirectory;

        $temporaryDirectory = realpath(sys_get_temp_dir());
        Assert::string($temporaryDirectory);

        $this->projectDirectory = $temporaryDirectory . DIRECTORY_SEPARATOR . uniqid('rector_root_files_');
        foreach (self::ROOT_FILE_NAMES as $rootFileName) {
            FileSystem::write($this->projectDirectory . '/' . $rootFileName, '<?php' . PHP_EOL, null);
        }

        chdir($this->projectDirectory);
    }

    protected function tearDown(): void
    {
        chdir($this->originalWorkingDirectory);

        FileSystem::delete($this->projectDirectory);
    }

    public function testRootFilesAreSortedByName(): void
    {
        $rectorConfigBuilder = RectorConfig::configure()->withRootFiles();

        // the paths are part of the cache invalidation hash, so their order has to be stable across machines
        $expectedPaths = [
            $this->projectDirectory . DIRECTORY_SEPARATOR . '.php-cs-fixer.dist.php',
            $this->projectDirectory . DIRECTORY_SEPARATOR . 'deploy.php',
            $this->projectDirectory . DIRECTORY_SEPARATOR . 'ecs.php',
            $this->projectDirectory . DIRECTORY_SEPARATOR . 'rector.php',
        ];

        $this->assertSame($expectedPaths, $this->resolvePaths($rectorConfigBuilder));
    }

    /**
     * @return string[]
     */
    private function resolvePaths(RectorConfigBuilder $rectorConfigBuilder): array
    {
        $pathsReflectionProperty = new ReflectionProperty(RectorConfigBuilder::class, 'paths');

        /** @var string[] $paths */
        $paths = $pathsReflectionProperty->getValue($rectorConfigBuilder);

        return $paths;
    }
}
