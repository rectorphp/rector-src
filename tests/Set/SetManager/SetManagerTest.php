<?php

declare(strict_types=1);

namespace Rector\Tests\Set\SetManager;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Bridge\SetProviderCollector;
use Rector\Composer\InstalledPackageResolver;
use Rector\Set\Enum\SetGroup;
use Rector\Set\SetManager;
use Rector\Symfony\Set\TwigSetList;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class SetManagerTest extends AbstractLazyTestCase
{
    public function testMatchComposerTriggered(): void
    {
        $setManager = $this->createSetManagerWithProjectDirectory(getcwd());

        $twigComposerTriggeredSet = $setManager->matchComposerTriggered(SetGroup::TWIG);
        $this->assertCount(6, $twigComposerTriggeredSet);
    }

    /**
     * @param string[] $expectedSets
     */
    #[DataProvider('provideInstalledTwigData')]
    public function testByVersion(string $projectDirectory, array $expectedSets): void
    {
        $setManager = $this->createSetManagerWithProjectDirectory($projectDirectory);

        $composerTriggeredSets = $setManager->matchBySetGroups([SetGroup::TWIG]);

        $this->assertCount(count($expectedSets), $composerTriggeredSets);
        $this->assertSame($expectedSets, $composerTriggeredSets);
    }

    /**
     * @return Iterator<(array<int, array<bool>>|array<int, array<int, bool>>|array<int, array<int, non-empty-string>>|array<int, string>)>
     */
    public static function provideInstalledTwigData(): Iterator
    {
        // here we cannot used features coming up in 2.4, as we only have 2.0
        yield [__DIR__ . '/Fixture/project-twig-20', [realpath(TwigSetList::TWIG_20)]];

        yield [__DIR__ . '/Fixture/project-twig-24', [realpath(TwigSetList::TWIG_20), realpath(TwigSetList::TWIG_24)]];

        yield [
            __DIR__ . '/Fixture/project-twig-127',
            [realpath(TwigSetList::TWIG_112), realpath(TwigSetList::TWIG_127)]];
    }

    private function createSetManagerWithProjectDirectory(string $projectDirectory): SetManager
    {
        $setProviderCollector = new SetProviderCollector();
        $installedPackageResolver = new InstalledPackageResolver($projectDirectory);

        return new SetManager($setProviderCollector, $installedPackageResolver);
    }
}
