<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding;

use Rector\Configuration\PhpLevelSetResolver;
use Rector\Contract\Rector\RectorInterface;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\DeprecatedAtVersionInterface;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

final class PhpVersionBoundsInvariantTest extends AbstractLazyTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Keep this test isolated from shared container state between tests.
        self::$rectorConfig = null;

        $allPhpLevelSets = PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_10);
        self::getContainer()->sets($allPhpLevelSets);
    }

    public function testMinPhpVersionIsNotHigherThanDeprecatedAtVersion(): void
    {
        /** @var list<MinPhpVersionInterface&DeprecatedAtVersionInterface> $dualVersionRectors */
        $dualVersionRectors = array_filter(
            [...self::getContainer()->tagged(RectorInterface::class)],
            fn (RectorInterface $rector): bool => (
                $rector instanceof MinPhpVersionInterface
                && $rector instanceof DeprecatedAtVersionInterface
            )
        );

        $violations = array_filter(array_map(
            function (MinPhpVersionInterface&DeprecatedAtVersionInterface $rector): ?string {
                $minimumPhpVersion = $rector->provideMinPhpVersion();
                $deprecatedAtVersion = $rector->provideDeprecatedAtVersion();

                if ($minimumPhpVersion <= $deprecatedAtVersion) {
                    return null;
                }

                return sprintf(
                    '%s has provideMinPhpVersion()=%d and provideDeprecatedAtVersion()=%d',
                    $rector::class,
                    $minimumPhpVersion,
                    $deprecatedAtVersion
                );
            },
            $dualVersionRectors
        ));

        $this->assertNotEmpty($dualVersionRectors, 'No dual-version rectors were inspected.');
        $this->assertSame([], $violations, implode(PHP_EOL, $violations));
    }
}
