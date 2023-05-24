<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;

final class ClassMethodAssignManipulator
{
    /**
     * @var array<string, string[]>
     */
    private array $alreadyAddedClassMethodNames = [];

    public function __construct(
        private readonly NodeFactory $nodeFactory,
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    public function addParameterAndAssignToMethod(
        ClassMethod $classMethod,
        string $name,
        ?Type $type,
        Assign $assign
    ): void {
        if ($this->hasMethodParameter($classMethod, $name)) {
            return;
        }

        $classMethod->params[] = $this->nodeFactory->createParamFromNameAndType($name, $type);
        $classMethod->stmts[] = new Expression($assign);

        $classMethodHash = spl_object_hash($classMethod);
        $this->alreadyAddedClassMethodNames[$classMethodHash][] = $name;
    }

    private function hasMethodParameter(ClassMethod $classMethod, string $name): bool
    {
        foreach ($classMethod->params as $param) {
            if ($this->nodeNameResolver->isName($param->var, $name)) {
                return true;
            }
        }

        $classMethodHash = spl_object_hash($classMethod);
        if (! isset($this->alreadyAddedClassMethodNames[$classMethodHash])) {
            return false;
        }

        return in_array($name, $this->alreadyAddedClassMethodNames[$classMethodHash], true);
    }
}
