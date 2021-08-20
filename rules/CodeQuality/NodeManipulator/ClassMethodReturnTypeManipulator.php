<?php

declare(strict_types=1);


namespace Rector\CodeQuality\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\NodeAnalyzer\ParamAnalyzer;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class ClassMethodReturnTypeManipulator
{
    /**
     * @var string[]
     */
    private const METHODS_RETURNING_CLASS_INSTANCE_MAP = [
        'add', 'modify', MethodName::SET_STATE, 'setDate', 'setISODate', 'setTime', 'setTimestamp', 'setTimezone', 'sub',
    ];

    public function __construct(
        private PhpDocInfoFactory $phpDocInfoFactory,
        private PhpDocTypeChanger $phpDocTypeChanger,
        private NodeTypeResolver $nodeTypeResolver,
        private ParamAnalyzer $paramAnalyzer,
        private NodeNameResolver $nodeNameResolver,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    )
    {
    }

    public function changeReturnType(ClassMethod $classMethod): ?ClassMethod
    {
        $classMethod = $this->refactorFunctionParameters($classMethod);

        /** @var FullyQualified|null $returnType */
        $returnType = $classMethod->returnType;
        if ($returnType === null) {
            return null;
        }

        $isNullable = false;
        if ($returnType instanceof NullableType) {
            $isNullable = true;
            $returnType = $returnType->type;
        }
        if (! $this->nodeTypeResolver->isObjectType($returnType, new ObjectType('DateTime'))) {
            return null;
        }

        $classMethod->returnType = new FullyQualified('DateTimeInterface');
        if ($isNullable) {
            $classMethod->returnType = new NullableType($classMethod->returnType);
        }


        $types = $this->determinePhpDocTypes($classMethod->returnType);
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $this->phpDocTypeChanger->changeReturnType($phpDocInfo, new UnionType($types));

        return $classMethod;
    }


    private function refactorFunctionParameters(ClassMethod $node): ClassMethod
    {
        foreach ($node->getParams() as $param) {
            if (! $this->nodeTypeResolver->isObjectType($param, new ObjectType('DateTime'))) {
                continue;
            }

            // Do not refactor if node's type is a child class of \DateTime (can have wider API)
            $paramType = $this->nodeTypeResolver->resolve($param);
            if (! $paramType->isSuperTypeOf(new ObjectType('DateTime'))->yes()) {
                continue;
            }


            $this->refactorParamTypeHint($param);
            $this->refactorParamDocBlock($param, $node);
            $this->refactorMethodCalls($param, $node);
        }

        return $node;
    }


    private function refactorParamTypeHint(Param $param): void
    {
        $fullyQualified = new FullyQualified('DateTimeInterface');
        if ($this->paramAnalyzer->isNullable($param)) {
            $param->type = new NullableType($fullyQualified);
            return;
        }

        $param->type = $fullyQualified;
    }

    private function refactorParamDocBlock(Param $param, ClassMethod $classMethod): void
    {
        $types = $this->determinePhpDocTypes($param);

        $paramName = $this->nodeNameResolver->getName($param->var);
        if ($paramName === null) {
            throw new ShouldNotHappenException();
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $this->phpDocTypeChanger->changeParamType($phpDocInfo, new UnionType($types), $param, $paramName);
    }

    private function refactorMethodCalls(Param $param, ClassMethod $classMethod): void
    {
        if ($classMethod->stmts === null) {
            return;
        }

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($classMethod->stmts, function (Node $node) use ($param): void {
            if (! ($node instanceof MethodCall)) {
                return;
            }

            $this->refactorMethodCall($param, $node);
        });
    }

    private function refactorMethodCall(Param $param, MethodCall $methodCall): void
    {
        $paramName = $this->nodeNameResolver->getName($param->var);
        if ($paramName === null) {
            return;
        }
        if ($this->shouldSkipMethodCallRefactor($paramName, $methodCall)) {
            return;
        }

        $assign = new Assign(new Variable($paramName), $methodCall);

        /** @var Node $parent */
        $parent = $methodCall->getAttribute(AttributeKey::PARENT_NODE);
        if ($parent instanceof Arg) {
            $parent->value = $assign;
            return;
        }

        if (! $parent instanceof Expression) {
            return;
        }

        $parent->expr = $assign;
    }

    private function shouldSkipMethodCallRefactor(string $paramName, MethodCall $methodCall): bool
    {
        if (! $this->nodeNameResolver->isName($methodCall->var, $paramName)) {
            return true;
        }

        if (! $this->nodeNameResolver->isNames($methodCall->name, self::METHODS_RETURNING_CLASS_INSTANCE_MAP)) {
            return true;
        }

        $parentNode = $methodCall->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof Node) {
            return true;
        }

        return $parentNode instanceof Assign;
    }

    /**
     * @return Type[]
     */
    private function determinePhpDocTypes(Node $node): array
    {
        $types = [
            new ObjectType('DateTime'),
            new ObjectType('DateTimeImmutable')
        ];

        if ($this->canHaveNullType($node)) {
            $types[] = new NullType();
        }

        return $types;
    }

    private function canHaveNullType(Node $node): bool
    {
        if ($node instanceof Param) {
            return $this->paramAnalyzer->isNullable($node);
        }

        return $node instanceof NullableType;
    }
}
