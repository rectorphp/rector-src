<?php

namespace Rector\Tests\Composer;

use Composer\InstalledVersions;
use PHPUnit\Framework\TestCase;
use Rector\Composer\ComposerContextSwitcher;

class ComposerContextSwitcherTest extends TestCase
{
    public function setUp(): void
    {
        InstalledVersions::getAllRawData();
    }

    public function test_it_can_load_different_composer()
    {
        $contextSwitcher = new ComposerContextSwitcher(__DIR__ . '/Fixture/vendor');

        $this->assertFalse(InstalledVersions::isInstalled('fixture/fake-package'));

        $contextSwitcher->loadTargetDependencies();
        $contextSwitcher->setComposerToTargetDependencies();

        $this->assertTrue(InstalledVersions::isInstalled('fixture/fake-package'));
    }

    public function test_it_can_reset_the_composer_class_to_its_own_dependencies()
    {
        $contextSwitcher = new ComposerContextSwitcher(__DIR__ . '/Fixture/vendor');

        $contextSwitcher->loadTargetDependencies();
        $contextSwitcher->setComposerToTargetDependencies();
        $contextSwitcher->reset();

        $this->assertFalse(InstalledVersions::isInstalled('fixture/fake-package'));
    }

    public function test_it_can_provide_the_state_of_the_dependencies_loaded()
    {
        $contextSwitcher = new ComposerContextSwitcher(__DIR__ . '/Fixture/vendor');

        $this->assertFalse($contextSwitcher->hasTargetDependencies());

        $contextSwitcher->loadTargetDependencies();
        $contextSwitcher->setComposerToTargetDependencies();

        $this->assertTrue($contextSwitcher->hasTargetDependencies());

        $contextSwitcher->reset();

        $this->assertFalse($contextSwitcher->hasTargetDependencies());
    }

    public function test_it_handles_the_installed_php_file_not_existing()
    {
        $this->expectException(\InvalidArgumentException::class);
        // /Fixture/vendor2 doesn't exist in the fixture folder
        $contextSwitcher = new ComposerContextSwitcher(__DIR__ . '/Fixture/vendor2');

        $contextSwitcher->loadTargetDependencies();
    }

    public function test_it_handles_the_installed_php_file_being_the_wrong_format()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an array. Got: NULL');
        $contextSwitcher = new ComposerContextSwitcher(__DIR__ . '/Fixture/vendor-error');

        $contextSwitcher->loadTargetDependencies();
    }
}
