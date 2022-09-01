<?php

declare(strict_types=1);

namespace Rector\Tests\PSR4\Composer;

use Nette\Utils\FileSystem;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\ValueObject\Application\File;
use Rector\PSR4\Composer\PSR4NamespaceMatcher;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class PSR4NamespaceMatcherTest extends AbstractTestCase
{
    private PSR4NamespaceMatcher $psr4NamespaceMatcher;

    protected function setUp(): void
    {
        $this->boot();
        $this->psr4NamespaceMatcher = $this->getService(PSR4NamespaceMatcher::class);
    }

    public function test(): void
    {
        $filePath = __DIR__ . '/Fixture-dashed/Config.php';
        $file = new File($filePath, FileSystem::read($filePath));

        $expectedNamespace = $this->psr4NamespaceMatcher->getExpectedNamespace($file, new FileWithoutNamespace([]));
        $this->assertNull($expectedNamespace);
    }
}
