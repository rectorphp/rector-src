<?php

declare(strict_types=1);

namespace Rector\NodeCollector\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\TypeWithClassName;
use Rector\NodeCollector\ValueObject\ArrayCallable;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ArrayCallableMethodReferenceAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    /**
     * Matches array like: "[$this, 'methodName']" → ['ClassName', 'methodName']
     */
    public function match(Array_ $array): ?ArrayCallable
    {
        $arrayItems = $array->items;
        if (count($arrayItems) !== 2) {
            return null;
        }

        if ($array->items[0] === null) {
            return null;
        }

        if ($array->items[1] === null) {
            return null;
        }

        // $this, self, static, FQN
        $firstItemValue = $array->items[0]->value;
        if (! $this->isThisVariable($firstItemValue)) {
            return null;
        }

        $secondItemValue = $array->items[1]->value;
        if (! $secondItemValue instanceof String_) {
            return null;
        }

        $calleeType = $this->nodeTypeResolver->resolve($firstItemValue);
        if (! $calleeType instanceof TypeWithClassName) {
            return null;
        }

        $className = $calleeType->getClassName();

        // ...

        $methodName = $secondItemValue->value;
//        $className = $array->getAttribute(AttributeKey::CLASS_NAME);
//        if ($className === null) {
//            return null;
//        }

        // required static calls, are not array callable per-se
        if ($this->isCallbackAtFunctionNames($array, ['register_shutdown_function', 'forward_static_call'])) {
            return null;
        }

        return new ArrayCallable($className, $methodName);
    }

    private function isThisVariable(Expr $expr): bool
    {
        // $this
        if ($expr instanceof Variable) {
            return true;
        }

        if ($expr instanceof PropertyFetch) {
            return true;
        }

        if ($expr instanceof ClassConstFetch) {
            if (! $this->nodeNameResolver->isName($expr->name, 'class')) {
                return false;
            }

            // self::class, static::class
            if ($this->nodeNameResolver->isNames($expr->class, ['self', 'static'])) {
                return true;
            }

            /** @var string|null $className */
            $className = $expr->getAttribute(AttributeKey::CLASS_NAME);

            if ($className === null) {
                return false;
            }

            return $this->nodeNameResolver->isName($expr->class, $className);
        }

        return false;
    }

    /**
     * @param string[] $functionNames
     */
    private function isCallbackAtFunctionNames(Array_ $array, array $functionNames): bool
    {
        $parentNode = $array->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof Arg) {
            return false;
        }

        $parentParentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentParentNode instanceof FuncCall) {
            return false;
        }

        return $this->nodeNameResolver->isNames($parentParentNode, $functionNames);
    }
}
