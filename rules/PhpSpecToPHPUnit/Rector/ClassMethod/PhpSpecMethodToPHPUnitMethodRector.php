<?php

declare(strict_types=1);

namespace Rector\PhpSpecToPHPUnit\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\ValueObject\MethodName;
use Rector\PhpSpecToPHPUnit\Naming\PhpSpecRenaming;
use Rector\PhpSpecToPHPUnit\PHPUnitTypeDeclarationDecorator;
use Rector\PhpSpecToPHPUnit\Rector\AbstractPhpSpecToPHPUnitRector;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;

/**
 * @see \Rector\Tests\PhpSpecToPHPUnit\Rector\Variable\PhpSpecToPHPUnitRector\PhpSpecToPHPUnitRectorTest
 */
final class PhpSpecMethodToPHPUnitMethodRector extends AbstractPhpSpecToPHPUnitRector
{
    public function __construct(
        private PHPUnitTypeDeclarationDecorator $phpUnitTypeDeclarationDecorator,
        private PhpSpecRenaming $phpSpecRenaming,
        private VisibilityManipulator $visibilityManipulator,
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isInPhpSpecBehavior($node)) {
            return null;
        }

        if ($this->isName($node, 'letGo')) {
            $node->name = new Identifier(MethodName::TEAR_DOWN);
            $this->visibilityManipulator->makeProtected($node);
            $this->phpUnitTypeDeclarationDecorator->decorate($node);
        } elseif ($this->isName($node, 'let')) {
            $node->name = new Identifier(MethodName::SET_UP);
            $this->visibilityManipulator->makeProtected($node);
            $this->phpUnitTypeDeclarationDecorator->decorate($node);
        } elseif ($node->isPublic()) {
            $this->processTestMethod($node);
        } else {
            return null;
        }

        return $node;
    }

    private function processTestMethod(ClassMethod $classMethod): void
    {
        // special case, @see https://johannespichler.com/writing-custom-phpspec-matchers/
        if ($this->isName($classMethod, 'getMatchers')) {
            return;
        }

        // change name to phpunit test case format
        $this->phpSpecRenaming->renameMethod($classMethod);

        // reorder instantiation + expected exception
        $previousStmt = null;
        foreach ((array) $classMethod->stmts as $key => $stmt) {
            $printedStmt = $this->print($stmt);

            if ($previousStmt && \str_contains($printedStmt, 'duringInstantiation')) {
                $printedPreviousStmt = $this->print($previousStmt);
                if (\str_contains($printedPreviousStmt, 'beConstructedThrough')) {
                    $classMethod->stmts[$key - 1] = $stmt;
                    $classMethod->stmts[$key] = $previousStmt;
                }
            }

            $previousStmt = $stmt;
        }
    }
}
