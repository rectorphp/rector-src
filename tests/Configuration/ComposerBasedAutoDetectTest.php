<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Rector\Configuration\RectorConfigBuilder;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Symfony\Set\TwigSetList;
use ReflectionClass;

final class ComposerBasedAutoDetectTest extends TestCase
{
    private string $originalWorkingDirectory;

    protected function setUp(): void
    {
        $this->originalWorkingDirectory = (string) getcwd();
        chdir(__DIR__ . '/Fixture/ComposerBasedAutoDetect/doctrine_project');
    }

    protected function tearDown(): void
    {
        chdir($this->originalWorkingDirectory);
    }

    /**
     * symfony/console is installed as well, but only symfony/framework-bundle marks a Symfony project
     */
    public function testNoArgumentLoadsSetsOfInstalledFrameworks(): void
    {
        $rectorConfigBuilder = new RectorConfigBuilder()
            ->withComposerBased();

        $this->assertSame(
            [PHPUnitSetList::COMPOSER_BASED, DoctrineSetList::COMPOSER_BASED],
            $this->readSets($rectorConfigBuilder)
        );
    }

    public function testExplicitArgumentSkipsDetection(): void
    {
        $rectorConfigBuilder = new RectorConfigBuilder()
            ->withComposerBased(twig: true);

        $this->assertSame([TwigSetList::COMPOSER_BASED], $this->readSets($rectorConfigBuilder));
    }

    public function testExplicitFalseArgumentSkipsDetection(): void
    {
        $rectorConfigBuilder = new RectorConfigBuilder()
            ->withComposerBased(phpunit: false);

        $this->assertSame([], $this->readSets($rectorConfigBuilder));
    }

    /**
     * @return string[]
     */
    private function readSets(RectorConfigBuilder $rectorConfigBuilder): array
    {
        $sets = new ReflectionClass($rectorConfigBuilder)
            ->getProperty('sets')
            ->getValue($rectorConfigBuilder);

        $this->assertIsArray($sets);

        return $sets;
    }
}
