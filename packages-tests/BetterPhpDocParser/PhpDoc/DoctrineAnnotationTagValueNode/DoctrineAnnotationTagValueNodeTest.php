<?php

namespace Rector\Tests\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;

use Nette\Utils\FileSystem as UtilsFileSystem;
use PhpParser\Node;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

class DoctrineAnnotationTagValueNodeTest extends AbstractLazyTestCase
{
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
        $filePath = __DIR__ . '/FixtureNested/DoctrineNestedClassAnnotation.php';
        $file = new File($filePath, UtilsFileSystem::read($filePath));

        $stmtsAndTokens = $this->rectorParser->parseFileToStmtsAndTokens($file->getFilePath());
        $oldStmts = $stmtsAndTokens->getStmts();
        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file, $oldStmts);

        $classStmt = null;

        foreach ($newStmts as $node) {
            if ($node->stmts === null) {
                continue;
            }

            foreach ($node->stmts as $stmt) {
                if (!$stmt instanceof Node\Stmt\Class_) {
                    continue;
                }

                $phpDocInfo = $this->phpDocInfoFactory->createFromNode($stmt);
                $phpDocNode = $phpDocInfo->getPhpDocNode();

                foreach ($phpDocNode->children as $key => $phpDocChildNode) {
                    $phpDocChildNode->setAttribute('start_and_end',null);
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
 * @Mapping\Table(name="doctrine_entity", uniqueConstraints={@UniqueConstraint(name="property")})
 */', $classDocComment
        );

    }

    private function printNodePhpDocInfoToString(Node $node): string
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        return $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
    }
}