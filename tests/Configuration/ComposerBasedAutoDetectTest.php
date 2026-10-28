<?php

declare(strict_types=1);

namespace Rector\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Rector\Configuration\ComposerBasedTriggerPackages;
use Rector\Configuration\RectorConfigBuilder;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Set\TwigSetList;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class ComposerBasedAutoDetectTest extends TestCase
{
    private string $originalWorkingDirectory;

    protected function setUp(): void
    {
        $this->originalWorkingDirectory = (string) getcwd();
        $this->changeProjectDirectory('installed_packages');
    }

    protected function tearDown(): void
    {
        chdir($this->originalWorkingDirectory);
    }

    /**
     * doctrine/dbal and symfony/console are enough to load their sets, no doctrine/orm or symfony/framework-bundle needed
     */
    public function testNoArgumentLoadsSetsOfInstalledPackages(): void
    {
        $rectorConfigBuilder = new RectorConfigBuilder()
            ->withComposerBased();

        $this->assertSame(
            [PHPUnitSetList::COMPOSER_BASED, DoctrineSetList::COMPOSER_BASED, SymfonySetList::COMPOSER_BASED],
            $this->readSets($rectorConfigBuilder)
        );
    }

    public function testNoArgumentWithoutTriggerPackagesLoadsNothing(): void
    {
        $this->changeProjectDirectory('no_trigger_packages');

        $rectorConfigBuilder = new RectorConfigBuilder()
            ->withComposerBased();

        $this->assertSame([], $this->readSets($rectorConfigBuilder));
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

    public function testEveryArgumentHasTriggerPackages(): void
    {
        $argumentNames = array_map(
            static fn (ReflectionParameter $reflectionParameter): string => $reflectionParameter->getName(),
            new ReflectionMethod(RectorConfigBuilder::class, 'withComposerBased')->getParameters()
        );

        // deprecated, no longer applied
        $argumentNames = array_values(array_diff($argumentNames, ['netteUtils']));

        $this->assertSame($argumentNames, array_keys(ComposerBasedTriggerPackages::PACKAGES_BY_ARGUMENT));
    }

    private function changeProjectDirectory(string $projectName): void
    {
        chdir(__DIR__ . '/Fixture/ComposerBasedAutoDetect/' . $projectName);
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
