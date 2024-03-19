<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Rector\Application\VersionResolver;
use Rector\Utils\Compiler\Unprefixer;

require_once __DIR__ . '/vendor/autoload.php';

// remove phpstan, because it is already prefixed in its own scope
$dateTime = DateTime::from('now');
$timestamp = $dateTime->format('Ym');

// @see https://github.com/humbug/php-scoper/blob/master/docs/further-reading.md
$polyfillsBootstraps = array_map(
    static fn (SplFileInfo $fileInfo): string => $fileInfo->getPathname(),
    iterator_to_array(
        Finder::create()
            ->files()
            ->in(__DIR__ . '/vendor/symfony/polyfill-*')
            ->name('bootstrap*.php'),
        false,
    ),
);

// see https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#configuration
return [
    'prefix' => 'RectorPrefix' . $timestamp,

    // exclude
    'exclude-classes' => [
        'PHPUnit\Framework\Constraint\IsEqual',
        'PHPUnit\Framework\TestCase',
        'PHPUnit\Framework\ExpectationFailedException',
    ],
    'exclude-namespaces' => [
        '#^Rector#',
        '#^PhpParser#',
        '#^PHPStan#',
        '#^Symplify\\\\RuleDocGenerator#',
        '#^Symfony\\\\Polyfill#',
    ],
    'exclude-files' => [...$polyfillsBootstraps],

    // expose
    'expose-classes' => ['Normalizer'],
    'expose-functions' => ['u', 'b', 's', 'trigger_deprecation', 'dump_with_depth', 'dn', 'dump_node', 'print_node'],
    'expose-constants' => ['__RECTOR_RUNNING__', '#^SYMFONY\_[\p{L}_]+$#'],

    'patchers' => [
        // fix short import bug, @see https://github.com/rectorphp/rector-scoper-017/blob/23f3256a6f5a18483d6eb4659d69ba117501e2e3/vendor/nikic/php-parser/lib/PhpParser/Builder/Declaration.php#L6
        static fn (string $filePath, string $prefix, string $content): string => str_replace(
            sprintf('use %s\PhpParser;', $prefix),
            'use PhpParser;',
            $content
        ),

        static fn (string $filePath, string $prefix, string $content): string =>
            // comment out
            str_replace('\\' . $prefix . '\trigger_deprecation(', '// \trigger_deprecation(', $content),

        static function (string $filePath, string $prefix, string $content): string {
            if (! \str_ends_with($filePath, 'src/Application/VersionResolver.php')) {
                return $content;
            }

            $releaseDateTime = VersionResolver::resolverReleaseDateTime();

            return strtr(
                $content,
                [
                    '@package_version@' => VersionResolver::resolvePackageVersion(),
                    '@release_date@' => $releaseDateTime->format('Y-m-d H:i:s'),
                ]
            );
        },

        // un-prefix composer plugin
        static function (string $filePath, string $prefix, string $content): string {
            if (! \str_ends_with($filePath, 'vendor/rector/extension-installer/src/Plugin.php')) {
                return $content;
            }

            // see https://regex101.com/r/v8zRMm/1
            return Strings::replace($content, '#' . $prefix . '\\\\Composer\\\\#', 'Composer\\');
        },

        // unprefix string classes, as they're string on purpose - they have to be checked in original form, not prefixed
        static function (string $filePath, string $prefix, string $content): string {
            // skip vendor, expect rector packages
            if (\str_contains($filePath, 'vendor/') && ! \str_contains($filePath, 'vendor/rector')) {
                return $content;
            }

            // skip bin/rector.php for composer autoload class
            if (\str_ends_with($filePath, 'bin/rector.php')) {
                return $content;
            }

            return Unprefixer::unprefixQuoted($content, $prefix);
        },

        static function (string $filePath, string $prefix, string $content): string {
            if (! \str_ends_with($filePath, 'vendor/nette/utils/src/Utils/Strings.php')) {
                return $content;
            }

            return str_replace(
                'return self::pcre(\'preg_replace_callback\', [$pattern, $replacement, $subject, $limit, 0, $flags]);',
                <<<'CODE_REPLACE'
if (PHP_VERSION_ID < 70400) {
    return self::pcre(\'preg_replace_callback\', [$pattern, $replacement, $subject, $limit]);
}

return self::pcre(\'preg_replace_callback\', [$pattern, $replacement, $subject, $limit, 0, $flags]);
CODE_REPLACE,
                $content
            );
        },
    ],
];
