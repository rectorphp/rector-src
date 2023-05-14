<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Util;

use Rector\Core\Kernel\RectorKernel;
use Rector\Core\Util\FileHasher;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\SmartFileSystem\SmartFileSystem;

final class FileHasherTest extends AbstractTestCase {
        private FileHasher $fileHasher;

        private SmartFileSystem $smartFileSystem;

        protected function setUp(): void
        {
            $this->boot();

            $this->fileHasher = $this->getService(FileHasher::class);
            $this->smartFileSystem = new SmartFileSystem();
        }

        public function testHash(): void
        {
            if (PHP_VERSION_ID < 80100) {
                $this->markTestSkipped('xxh128 is not available on PHP 8.0');
            }

            $hash = $this->fileHasher->hash('some string');
            $this->assertSame('8df638f91bacc826bf50c04efd7df1b1', $hash);
        }

        public function testHashFiles(): void
        {
            if (PHP_VERSION_ID < 80100) {
                $this->markTestSkipped('xxh128 is not available on PHP 8.0');
            }

            $dir = sys_get_temp_dir();
            $file = $dir.'/FileHasherTest-fixture.txt';

            try {
                $this->smartFileSystem->dumpFile($file, 'some string');

                $hash = $this->fileHasher->hashFiles([$file]);
                $this->assertSame('8df638f91bacc826bf50c04efd7df1b1', $hash);
            } finally {
                $this->smartFileSystem->remove($file);
            }
        }

        public function testHashFilesWithEmptyArray(): void
        {
            $hash = $this->fileHasher->hashFiles([]);
            $this->assertSame('', $hash);
        }
}
