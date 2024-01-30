<?php

namespace Rector\Tests\VersionBonding\Composer;

use Composer\InstalledVersions;
use PHPUnit\Framework\TestCase;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\VersionBonding\Composer\ComposerContextSwitcher;

class ComposerContextSwitcherTest extends TestCase
{
    protected function setUp(): void
    {
        InstalledVersions::getAllRawData();
    }

    public function testItCanLoadDifferentComposer()
    {
        SimpleParameterProvider::setParameter(Option::VENDOR_PATH, __DIR__ . '/Fixture/vendor');
        $contextSwitcher = new ComposerContextSwitcher();

        $this->assertFalse(InstalledVersions::isInstalled('fixture/fake-package'));

        $contextSwitcher->loadTargetDependencies();
        $contextSwitcher->setComposerToTargetDependencies();

        $this->assertTrue(InstalledVersions::isInstalled('fixture/fake-package'));
    }

    public function testItCanResetTheComposerClassToItsOwnDependencies()
    {
        SimpleParameterProvider::setParameter(Option::VENDOR_PATH, __DIR__ . '/Fixture/vendor');
        $contextSwitcher = new ComposerContextSwitcher();

        $contextSwitcher->loadTargetDependencies();
        $contextSwitcher->setComposerToTargetDependencies();
        $contextSwitcher->reset();

        $this->assertFalse(InstalledVersions::isInstalled('fixture/fake-package'));
    }

    public function testItCanProvideTheStateOfTheDependenciesLoaded()
    {
        SimpleParameterProvider::setParameter(Option::VENDOR_PATH, __DIR__ . '/Fixture/vendor');
        $contextSwitcher = new ComposerContextSwitcher();

        $this->assertFalse($contextSwitcher->hasTargetDependencies());

        $contextSwitcher->loadTargetDependencies();
        $contextSwitcher->setComposerToTargetDependencies();

        $this->assertTrue($contextSwitcher->hasTargetDependencies());

        $contextSwitcher->reset();

        $this->assertFalse($contextSwitcher->hasTargetDependencies());
    }

    public function testItHandlesTheInstalledPhpFileNotExisting()
    {
        $this->expectException(\InvalidArgumentException::class);
        // /Fixture/vendor2 doesn't exist in the fixture folder
        SimpleParameterProvider::setParameter(Option::VENDOR_PATH, __DIR__ . '/Fixture/vendor2');
        $contextSwitcher = new ComposerContextSwitcher();

        $contextSwitcher->loadTargetDependencies();
    }

    public function testItHandlesTheInstalledPhpFileBeingTheWrongFormat()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an array. Got: NULL');
        SimpleParameterProvider::setParameter(Option::VENDOR_PATH, __DIR__ . '/Fixture/vendor-error');
        $contextSwitcher = new ComposerContextSwitcher();

        $contextSwitcher->loadTargetDependencies();
    }
}
