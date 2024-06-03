<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDoc\ArrayItemNode;

use Nette\Utils\FileSystem as UtilsFileSystem;
use PhpParser\Node\Stmt\Class_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\PhpParser\Parser\RectorParser;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ArrayItemNodeTest extends AbstractLazyTestCase
{
    private DocBlockUpdater $docBlockUpdater;

    private PhpDocInfoFactory $phpDocInfoFactory;

    private PhpDocInfoPrinter $phpDocInfoPrinter;

    private RectorParser $rectorParser;

    private NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator;

    protected function setUp(): void
    {
        $this->docBlockUpdater = $this->make(DocBlockUpdater::class);
        $this->phpDocInfoFactory = $this->make(PhpDocInfoFactory::class);
        $this->phpDocInfoPrinter = $this->make(PhpDocInfoPrinter::class);
        $this->rectorParser = $this->make(RectorParser::class);
        $this->nodeScopeAndMetadataDecorator = $this->make(NodeScopeAndMetadataDecorator::class);
    }

    public function testUpdateNestedClassAnnotation(): void
    {
        $filePath = __DIR__ . '/FixtureNested/DoctrineNestedClassAnnotation.php.inc';
        $fileContent = UtilsFileSystem::read($filePath);

        $stmtsAndTokens = $this->rectorParser->parseFileContentToStmtsAndTokens($fileContent);
        $oldStmts = $stmtsAndTokens->getStmts();
        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($filePath, $oldStmts);

        $classStmt = null;
        $classDocComment = null;

        foreach ($newStmts as $newStmt) {
            if ($newStmt->stmts === null) {
                continue;
            }

            foreach ($newStmt->stmts as $stmt) {
                if (! $stmt instanceof Class_) {
                    continue;
                }

                $phpDocInfo = $this->phpDocInfoFactory->createFromNode($stmt);
                $phpDocNode = $phpDocInfo->getPhpDocNode();

                foreach ($phpDocNode->children as $key => $phpDocChildNode) {
                    $phpDocChildNode->setAttribute('start_and_end', null);
                    $phpDocNode->children[$key] = $phpDocChildNode;
                }

                $classStmt = $stmt;
                break;
            }

            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($classStmt);
            $classDocComment = $this->printNodePhpDocInfoToString($classStmt);
        }

        $this->assertEquals(
            '/**
 * @ORM\Table(name="doctrine_entity", uniqueConstraints={@ORM\UniqueConstraint(name="property")})
 */',
            $classDocComment
        );
    }

    private function printNodePhpDocInfoToString(?Class_ $class): string
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($class);
        return $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
    }
}
