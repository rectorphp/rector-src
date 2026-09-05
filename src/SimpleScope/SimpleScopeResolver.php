<?php

declare(strict_types=1);

namespace Rector\SimpleScope;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\NodeFinder;
use Rector\SimpleType\ArrayType;
use Rector\SimpleType\BooleanType;
use Rector\SimpleType\Contract\SimpleTypeInterface;
use Rector\SimpleType\IntegerType;
use Rector\SimpleType\ObjectType;
use Rector\SimpleType\StringType;

// builds a PHPStan-free SimpleScope from params and local assigns, in source order
/**
 * @see \Rector\Tests\SimpleScope\SimpleScopeResolverTest
 */
final readonly class SimpleScopeResolver
{
    private NodeFinder $nodeFinder;

    public function __construct()
    {
        $this->nodeFinder = new NodeFinder();
    }

    /**
     * @param Node[] $stmts
     */
    public function resolve(array $stmts): SimpleScope
    {
        $simpleScope = new SimpleScope();

        foreach ($this->nodeFinder->findInstanceOf($stmts, Param::class) as $param) {
            $this->seedParam($simpleScope, $param);
        }

        foreach ($this->nodeFinder->findInstanceOf($stmts, Assign::class) as $assign) {
            if (! $assign->var instanceof Variable || ! is_string($assign->var->name)) {
                continue;
            }

            $simpleScope->setVariableType($assign->var->name, $simpleScope->getType($assign->expr));
        }

        return $simpleScope;
    }

    private function seedParam(SimpleScope $simpleScope, Param $param): void
    {
        if (! $param->var instanceof Variable || ! is_string($param->var->name)) {
            return;
        }

        $paramType = $this->resolveParamType($param);
        if (! $paramType instanceof SimpleTypeInterface) {
            return;
        }

        $simpleScope->setVariableType($param->var->name, $paramType);
    }

    private function resolveParamType(Param $param): ?SimpleTypeInterface
    {
        if ($param->type instanceof Name) {
            return new ObjectType($param->type->toString());
        }

        if (! $param->type instanceof Identifier) {
            return null;
        }

        return match ($param->type->toLowerString()) {
            'string' => new StringType(),
            'int' => new IntegerType(),
            'bool' => new BooleanType(),
            'array' => new ArrayType(),
            default => null,
        };
    }
}
