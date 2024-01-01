<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\FileSystem\FilePathHelper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

abstract class AbstractPhpDocInfoPrinterTestCase extends AbstractLazyTestCase
{
    protected FilePathHelper $filePathHelper;

    protected PhpDocInfoPrinter $phpDocInfoPrinter;

    private PhpDocInfoFactory $phpDocInfoFactory;

    protected function setUp(): void
    {
        $this->filePathHelper = $this->make(FilePathHelper::class);
        $this->phpDocInfoFactory = $this->make(PhpDocInfoFactory::class);
        $this->phpDocInfoPrinter = $this->make(PhpDocInfoPrinter::class);
    }

    protected function createPhpDocInfoFromDocCommentAndNode(string $docComment, Node $node): PhpDocInfo
    {
        $node->setDocComment(new Doc($docComment));
        return $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
    }
}
