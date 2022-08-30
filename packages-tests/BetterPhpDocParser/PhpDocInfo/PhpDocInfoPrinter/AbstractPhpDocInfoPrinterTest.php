<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter;

use Iterator;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Testing\PHPUnit\AbstractTestCase;

abstract class AbstractPhpDocInfoPrinterTest extends AbstractTestCase
{
    protected FilePathHelper $filePathHelper;

    protected PhpDocInfoPrinter $phpDocInfoPrinter;

    private PhpDocInfoFactory $phpDocInfoFactory;

    protected function setUp(): void
    {
        $this->boot();

        $this->filePathHelper = $this->getService(FilePathHelper::class);
        $this->phpDocInfoFactory = $this->getService(PhpDocInfoFactory::class);
        $this->phpDocInfoPrinter = $this->getService(PhpDocInfoPrinter::class);
    }

    protected function createPhpDocInfoFromDocCommentAndNode(string $docComment, Node $node): PhpDocInfo
    {
        $node->setDocComment(new Doc($docComment));
        return $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
    }

    protected function yieldFilesFromDirectory(string $directory, string $suffix = '*.php'): Iterator
    {
        return \Rector\Testing\Fixture\FixtureFileFinder::yieldDirectory($directory, $suffix);
    }
}
