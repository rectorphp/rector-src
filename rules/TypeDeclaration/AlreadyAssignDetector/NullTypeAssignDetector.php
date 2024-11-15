<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\AlreadyAssignDetector;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\NodeTraverser;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PHPStanStaticTypeMapper\DoctrineTypeAnalyzer;
use Rector\TypeDeclaration\Matcher\PropertyAssignMatcher;

/**
 * Should add extra null type
 */
final readonly class NullTypeAssignDetector
{
    public function __construct(
        private DoctrineTypeAnalyzer $doctrineTypeAnalyzer,
        private NodeTypeResolver $nodeTypeResolver,
        private PropertyAssignMatcher $propertyAssignMatcher,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    public function detect(ClassLike $classLike, string $propertyName): bool
    {
        $needsNullType = false;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($classLike->stmts, function (Node $node) use (
            $propertyName,
            &$needsNullType
        ): ?int {
            $expr = $this->matchAssignExprToPropertyName($node, $propertyName);
            if (! $expr instanceof Expr) {
                return null;
            }

            // not in doctrine property
            $staticType = $this->nodeTypeResolver->getType($expr);
            if ($this->doctrineTypeAnalyzer->isDoctrineCollectionWithIterableUnionType($staticType)) {
                $needsNullType = false;
                return \PhpParser\NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            return null;
        });

        return $needsNullType;
    }

    private function matchAssignExprToPropertyName(Node $node, string $propertyName): ?Expr
    {
        if (! $node instanceof Assign) {
            return null;
        }

        return $this->propertyAssignMatcher->matchPropertyAssignExpr($node, $propertyName);
    }
}
