<?php

declare(strict_types=1);

namespace Rector\Tests\Bin;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class PreloadTest extends TestCase
{
    private string $rootDirectory;

    protected function tearDown(): void
    {
        if (! isset($this->rootDirectory)) {
            return;
        }

        $process = Process::fromShellCommandline('rm -rf ' . escapeshellarg($this->rootDirectory));
        $process->run();
    }

    /**
     * bin/rector.php picks a preload file from two conditions: "I have my own vendor/", so a
     * monorepo checkout, and "I sit inside a project's vendor/". A checkout with its own vendor/
     * that also happens to be three levels below another vendor/ answers yes to both. Both preload
     * files declare isPHPStanTestPreloaded(), so loading both is a fatal error.
     */
    public function testPicksOnePreloadFileWhenBothConditionsHold(): void
    {
        $this->rootDirectory = sys_get_temp_dir() . '/rector-preload-test-' . uniqid();
        $repositoryDirectory = $this->rootDirectory . '/a/b/c/repo';
        mkdir($repositoryDirectory . '/bin', 0777, true);

        $vendorDirectory = realpath(__DIR__ . '/../../vendor');
        // the first condition, and the paths preload.php requires
        symlink($vendorDirectory, $repositoryDirectory . '/vendor');
        // the second condition, and the paths preload-split-package.php requires
        symlink($vendorDirectory, $this->rootDirectory . '/a/vendor');

        foreach (['bin/rector.php', 'preload.php', 'preload-split-package.php'] as $relativeFilePath) {
            copy(__DIR__ . '/../../' . $relativeFilePath, $repositoryDirectory . '/' . $relativeFilePath);
        }

        $process = Process::fromShellCommandline(PHP_BINARY . ' bin/rector.php --version', $repositoryDirectory);
        $process->run();

        $this->assertStringNotContainsString('Cannot redeclare', $process->getErrorOutput());
    }
}
