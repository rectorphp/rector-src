<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint;

use Iterator;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\Testing\Fixture\FixtureFileFinder;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class TagValueNodeReprintTest extends AbstractTestCase
{
    /**
     * @var string
     * @see https://regex101.com/r/0UHYBt/1
     */
    private const SPLIT_LINE_REGEX = "#-----\n#";

    private FileInfoParser $fileInfoParser;

    private BetterNodeFinder $betterNodeFinder;

    private PhpDocInfoPrinter $phpDocInfoPrinter;

    private PhpDocInfoFactory $phpDocInfoFactory;

    private FilePathHelper $filePathHelper;

    protected function setUp(): void
    {
        $this->boot();

        $this->fileInfoParser = $this->getService(FileInfoParser::class);
        $this->filePathHelper = $this->getService(FilePathHelper::class);
        $this->betterNodeFinder = $this->getService(BetterNodeFinder::class);
        $this->phpDocInfoPrinter = $this->getService(PhpDocInfoPrinter::class);
        $this->phpDocInfoFactory = $this->getService(PhpDocInfoFactory::class);
    }

    #[DataProvider('provideData')]
    #[DataProvider('provideDataNested')]
    public function test(string $filePath): void
    {
        $fixtureFileContents = FileSystem::read($filePath);

        [$fileContents, $nodeClass, $tagValueNodeClasses] = Strings::split(
            $fixtureFileContents,
            self::SPLIT_LINE_REGEX
        );

        $nodeClass = trim((string) $nodeClass);
        $tagValueNodeClasses = $this->splitListByEOL($tagValueNodeClasses);

        $fixtureFilePath = FixtureTempFileDumper::dump($fileContents);

        foreach ($tagValueNodeClasses as $tagValueNodeClass) {
            $this->doTestPrintedPhpDocInfo($fixtureFilePath, $tagValueNodeClass, $nodeClass);
        }
    }

    public static function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/Fixture');
    }

    public static function provideDataNested(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/FixtureNested');
    }

    /**
     * @param class-string $annotationClass
     * @param class-string<Node> $nodeClass
     */
    private function doTestPrintedPhpDocInfo(string $filePath, string $annotationClass, string $nodeClass): void
    {
        $nodeWithPhpDocInfo = $this->parseFileAndGetFirstNodeOfType($filePath, $nodeClass);

        $docComment = $nodeWithPhpDocInfo->getDocComment();
        if (! $docComment instanceof Doc) {
            throw new ShouldNotHappenException(sprintf(
                'Doc comments for "%s" file cannot not be empty',
                $filePath
            ));
        }

        $originalDocCommentText = $docComment->getText();
        $printedPhpDocInfo = $this->printNodePhpDocInfoToString($nodeWithPhpDocInfo);

        $this->assertSame($originalDocCommentText, $printedPhpDocInfo);
        $this->doTestContainsTagValueNodeType($nodeWithPhpDocInfo, $annotationClass, $filePath);
    }

    /**
     * @return string[]
     */
    private function splitListByEOL(string $content): array
    {
        $trimmedContent = trim($content);
        return explode(PHP_EOL, $trimmedContent);
    }

    /**
     * @template T as Node
     * @param class-string<T> $nodeType
     * @return T
     */
    private function parseFileAndGetFirstNodeOfType(string $filePath, string $nodeType): Node
    {
        $nodes = $this->fileInfoParser->parseFileInfoToNodesAndDecorate($filePath);

        $node = $this->betterNodeFinder->findFirstInstanceOf($nodes, $nodeType);
        if (! $node instanceof Node) {
            throw new ShouldNotHappenException($filePath);
        }

        return $node;
    }

    private function printNodePhpDocInfoToString(Node $node): string
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        return $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
    }

    /**
     * @param class-string $annotationClass
     */
    private function doTestContainsTagValueNodeType(Node $node, string $annotationClass, string $filePath): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $hasByAnnotationClass = $phpDocInfo->hasByAnnotationClass($annotationClass);

        $relativeFilePath = $this->filePathHelper->relativePath($filePath);
        $this->assertTrue($hasByAnnotationClass, $relativeFilePath);
    }
}
