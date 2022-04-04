<?php

declare(strict_types=1);

namespace Rector\Core\Stubs;

use FilesystemIterator;
use Phar;
use RecursiveIteratorIterator;

final class PHPStanStubLoader
{
    /**
     * @var string[]
     */
    private const VENDOR_PATHS = [
        // 1. relative path with composer require rector/rector and run vendor/bin/rector
        'vendor',
        // 2. relative path with composer require rector/rector with symlink run vendor/bin/rector
        __DIR__ . '/../../vendor',
        // 3. run outside project like in https://getrector.org/ from docker, so it look up // vendor/rector/rector/bin/rector
        __DIR__ . '/../../../../../vendor',
    ];

    private bool $areStubsLoaded = false;

    /**
     * @see https://github.com/phpstan/phpstan/issues/4541#issuecomment-779434916
     *
     * Point possible vendor locations by use the __DIR__ as start to locate
     * @see https://github.com/rectorphp/rector/pull/5581 that may not detected in https://getrector.org/ which uses docker to run
     */
    public function loadStubs(): void
    {
        if ($this->areStubsLoaded) {
            return;
        }

        foreach (self::VENDOR_PATHS as $vendorPath) {
            $vendorPath = realpath($vendorPath);
            if ($vendorPath === false) {
                continue;
            }

            $stubs = $this->getStubPaths($vendorPath);
            if ($stubs === []) {
                continue;
            }

            foreach ($stubs as $stub) {
                require_once $stub;
            }

            $this->areStubsLoaded = true;

            // already loaded? stop loop
            break;
        }
    }

    /**
     * @return array<string>
     */
    private function getStubPaths(string $vendorPath): array
    {
        $pharPath = sprintf('phar://%s/phpstan/phpstan/phpstan.phar/stubs/runtime', $vendorPath);

        if (! is_dir($pharPath)) {
            return [];
        }

        $phar = new Phar($pharPath, FilesystemIterator::CURRENT_AS_FILEINFO);

        $files = [];
        foreach (new RecursiveIteratorIterator($phar) as $file) {
            $files[] = (string) $file;
        }

        return $files;
    }
}
