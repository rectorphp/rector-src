<?php

declare(strict_types=1);

namespace Rector\Php72\NodeFactory;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\VoidType;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class AnonymousFunctionFactory
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private BetterNodeFinder $betterNodeFinder,
        private NodeFactory $nodeFactory,
        private StaticTypeMapper $staticTypeMapper,
    ) {
    }

    /**
     * @param Param[] $params
     * @param Stmt[] $stmts
     * @param Identifier|Name|NullableType|UnionType|null $returnTypeNode
     */
    public function create(array $params, array $stmts, ?Node $returnTypeNode): Closure
    {
        $useVariables = $this->createUseVariablesFromParams($stmts, $params);

        $anonymousFunctionNode = new Closure();
        $anonymousFunctionNode->params = $params;

        foreach ($useVariables as $useVariable) {
            $anonymousFunctionNode->uses[] = new ClosureUse($useVariable);
        }

        if ($returnTypeNode instanceof Node) {
            $anonymousFunctionNode->returnType = $returnTypeNode;
        }

        $anonymousFunctionNode->stmts = $stmts;
        return $anonymousFunctionNode;
    }

    /**
     * @param Variable|PropertyFetch $expr
     */
    public function createFromPhpMethodReflection(PhpMethodReflection $phpMethodReflection, Expr $expr): Closure
    {
        /** @var FunctionVariantWithPhpDocs $functionVariantWithPhpDoc */
        $functionVariantWithPhpDoc = $phpMethodReflection->getVariants()[0];

        $anonymousFunction = new Closure();
        $newParams = $this->createParams($functionVariantWithPhpDoc->getParameters());

        $anonymousFunction->params = $newParams;

        $innerMethodCall = new MethodCall($expr, $phpMethodReflection->getName());
        $innerMethodCall->args = $this->nodeFactory->createArgsFromParams($newParams);

        if (! $functionVariantWithPhpDoc->getReturnType() instanceof MixedType) {
            $returnType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                $functionVariantWithPhpDoc->getReturnType()
            );
            $anonymousFunction->returnType = $returnType;
        }

        // does method return something?

        if (! $functionVariantWithPhpDoc->getReturnType() instanceof VoidType) {
            $anonymousFunction->stmts[] = new Return_($innerMethodCall);
        } else {
            $anonymousFunction->stmts[] = new Expression($innerMethodCall);
        }

        if ($expr instanceof Variable && ! $this->nodeNameResolver->isName($expr, 'this')) {
            $anonymousFunction->uses[] = new ClosureUse($expr);
        }

        return $anonymousFunction;
    }

    /**
     * @param Node[] $nodes
     * @param Param[] $paramNodes
     * @return Variable[]
     */
    private function createUseVariablesFromParams(array $nodes, array $paramNodes): array
    {
        $paramNames = [];
        foreach ($paramNodes as $paramNode) {
            $paramNames[] = $this->nodeNameResolver->getName($paramNode);
        }

        $variableNodes = $this->betterNodeFinder->findInstanceOf($nodes, Variable::class);

        /** @var Variable[] $filteredVariables */
        $filteredVariables = [];
        $alreadyAssignedVariables = [];
        foreach ($variableNodes as $variableNode) {
            // "$this" is allowed
            if ($this->nodeNameResolver-> isName($variableNode, 'this')) {
                continue;
            }

            $variableName = $this->nodeNameResolver->getName($variableNode);
            if ($variableName === null) {
                continue;
            }

            if (in_array($variableName, $paramNames, true)) {
                continue;
            }

            $parentNode = $variableNode->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentNode instanceof Assign) {
                $alreadyAssignedVariables[] = $variableName;
            }

            if ($this->nodeNameResolver->isNames($variableNode, $alreadyAssignedVariables)) {
                continue;
            }

            $filteredVariables[$variableName] = $variableNode;
        }

        return $filteredVariables;
    }

    /**
     * @param ParameterReflection[] $parameterReflections
     * @return Param[]
     */
    private function createParams(array $parameterReflections): array
    {
        $params = [];
        foreach ($parameterReflections as $parameterReflection) {
            $param = new Param(new Variable($parameterReflection->getName()));

            if (! $parameterReflection->getType() instanceof MixedType) {
                $paramType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($parameterReflection->getType());
                $param->type = $paramType;
            }

            $params[] = $param;
        }

        return $params;
    }
}
