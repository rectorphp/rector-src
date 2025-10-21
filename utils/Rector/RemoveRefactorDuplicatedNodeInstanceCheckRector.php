<?php

declare(strict_types=1);

namespace Rector\Utils\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveRefactorDuplicatedNodeInstanceCheckRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove refactor() method of Rector rule double check of $node instance, if already defined in @param type', []);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\ClassMethod::class];
    }

    /**
     * @param Node\Stmt\ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'refactor')) {
            return null;
        }

        if (! $node->isPublic()) {
            return null;
        }

        $firstStmt = $node->stmts[0] ?? null;
        if (! $firstStmt instanceof If_) {
            return null;
        }

        // remove already added properties

        if ($typesToAdd === []) {
            return null;
        }

        $hasChanged = false;

        foreach ($typesToAdd as $propertyNameToAdd => $propertyTypeToAdd) {
            // skip if property already exists
            if ($node->getProperty($propertyNameToAdd) instanceof Property) {
                continue;
            }

            $this->classDependencyManipulator->addConstructorDependency(
                $node,
                new PropertyMetadata($propertyNameToAdd, new ObjectType($propertyTypeToAdd))
            );

            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }
}
