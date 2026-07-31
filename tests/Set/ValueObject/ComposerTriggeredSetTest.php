<?php

declare(strict_types=1);

namespace Rector\Tests\Set\ValueObject;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Composer\ValueObject\InstalledPackage;
use Rector\Set\Enum\SetGroup;
use Rector\Set\ValueObject\ComposerTriggeredSet;

final class ComposerTriggeredSetTest extends TestCase
{
    #[DataProvider('provideData')]
    public function testMatchInstalledPackages(
        string $version,
        string $installedVersion,
        bool $expectedMatch
    ): void {
        $composerTriggeredSet = new ComposerTriggeredSet(
            SetGroup::PHPUNIT,
            'phpunit/phpunit',
            $version,
            __FILE__
        );

        $installedPackages = [
            'phpunit/phpunit' => new InstalledPackage('phpunit/phpunit', $installedVersion),
        ];

        $this->assertSame($expectedMatch, $composerTriggeredSet->matchInstalledPackages($installedPackages));
    }

    public static function provideData(): Iterator
    {
        // a bare version keeps the "this major version" behaviour
        yield ['10.0', '10.5.0.0', true];
        yield ['10.0', '11.0.0.0', false];
        yield ['10.0', '9.6.0.0', false];

        // a constraint is used as is
        yield ['>=10.0', '13.2.0.0', true];
        yield ['>=10.0', '9.6.0.0', false];
        yield ['>=10.0 <13.0', '12.5.0.0', true];
        yield ['>=10.0 <13.0', '13.0.0.0', false];
    }

    public function testSkipNotInstalledPackage(): void
    {
        $composerTriggeredSet = new ComposerTriggeredSet(
            SetGroup::PHPUNIT,
            'phpunit/phpunit',
            '>=10.0',
            __FILE__
        );

        $this->assertFalse($composerTriggeredSet->matchInstalledPackages([]));
    }
}
