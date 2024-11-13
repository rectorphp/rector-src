<?php

declare(strict_types=1);

namespace Rector\Tests\Set\SetManager;

use DG\BypassFinals;
use Rector\Bridge\SetProviderCollector;
use Rector\Composer\InstalledPackageResolver;
use Rector\Composer\ValueObject\InstalledPackage;
use Rector\Set\Enum\SetGroup;
use Rector\Set\SetManager;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class SetManagerTest extends AbstractLazyTestCase
{
    private SetManager $setManager;

    protected function setUp(): void
    {
        parent::setUp();

        BypassFinals::enable();

        $setProviderCollector = new SetProviderCollector();

        // fake that Twig 2.0 is installed, as data are fetched from currently installed packages
        $installedPackageResolverMock = $this->createMock(InstalledPackageResolver::class);
        $installedPackageResolverMock->method('resolve')
            ->willReturn([new InstalledPackage('twig/twig', '2.0.0')]);

        $this->setManager = new SetManager($setProviderCollector, $installedPackageResolverMock);
    }

    public function test(): void
    {
        $twigComposerTriggeredSet = $this->setManager->matchComposerTriggered(SetGroup::TWIG);
        $this->assertCount(6, $twigComposerTriggeredSet);
    }

    public function testByVersion(): void
    {
        $composerTriggeredSets = $this->setManager->matchBySetGroups([SetGroup::TWIG]);
        $this->assertCount(2, $composerTriggeredSets);
    }
}
