<?php

declare(strict_types=1);

namespace Rector\Utils\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\If_;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveRefactorDuplicatedNodeInstanceCheckRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove refactor() method of Rector rule double check of $node instance, if already defined in @param type',
            []
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
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

        if (! $firstStmt->cond instanceof Node\Expr\BooleanNot) {
            return null;
        }

        $booleanNot = $firstStmt->cond;

        if (! $booleanNot->expr instanceof Node\Expr\Instanceof_) {
            return null;
        }

        $instanceIf = $booleanNot->expr;
        $checkedClassType = $this->getType($instanceIf->class);

        if (! $checkedClassType instanceof ObjectType) {
            return null;
        }

        $classReflection = $checkedClassType->getClassReflection();

        if (! $classReflection->is(Node::class)) {
            return null;
        }

        $classMethodPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $paramType = $classMethodPhpDocInfo->getParamType('$node');
        if (! $paramType instanceof ObjectType) {
            return null;
        }

        if ($paramType instanceof ShortenedObjectType) {
            $className = $paramType->getFullyQualifiedName();
        } else {
            $className = $paramType->getClassName();
        }

        if ($className !== $checkedClassType->getClassName()) {
            return null;
        }

        unset($node->stmts[0]);

        return $node;
    }
}
