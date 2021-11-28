<?php

declare(strict_types=1);

namespace Rector\Arguments;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Arguments\Contract\ReplaceArgumentDefaultValueInterface;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\Core\PhpParser\Node\Value\ValueResolver;

final class ArgumentDefaultValueReplacer
{
    public function __construct(
        private NodeFactory $nodeFactory,
        private ValueResolver $valueResolver
    ) {
    }

    public function processReplaces(
        MethodCall | StaticCall | ClassMethod | FuncCall $node,
        ReplaceArgumentDefaultValueInterface $replaceArgumentDefaultValue
    ): ?Node {
        if ($node instanceof ClassMethod) {
            if (! isset($node->params[$replaceArgumentDefaultValue->getPosition()])) {
                return null;
            }

            return $this->processParams($node, $replaceArgumentDefaultValue);
        }

        if (! isset($node->args[$replaceArgumentDefaultValue->getPosition()])) {
            return null;
        }

        return $this->processArgs($node, $replaceArgumentDefaultValue);
    }

    /**
     * @param mixed $value
     */
    public function isDefaultValueMatched(?Expr $expr, $value): bool
    {
        // allow any values before, also allow param without default value
        if ($value === ReplaceArgumentDefaultValue::ANY_VALUE_BEFORE) {
            return true;
        }

        if ($expr === null) {
            return false;
        }

        if ($this->valueResolver->isValue($expr, $value)) {
            return true;
        }

        // ValueResolver::isValue returns false when default value is `null`
        return $value === null && $this->valueResolver->isNull($expr);
    }

    private function processParams(
        ClassMethod $classMethod,
        ReplaceArgumentDefaultValueInterface $replaceArgumentDefaultValue
    ): ?ClassMethod {
        $position = $replaceArgumentDefaultValue->getPosition();

        if (! $this->isDefaultValueMatched(
            $classMethod->params[$position]->default,
            $replaceArgumentDefaultValue->getValueBefore()
        )) {
            return null;
        }

        $classMethod->params[$position]->default = $this->normalizeValue($replaceArgumentDefaultValue->getValueAfter());
        return $classMethod;
    }

    private function processArgs(
        MethodCall | StaticCall | FuncCall $expr,
        ReplaceArgumentDefaultValueInterface $replaceArgumentDefaultValue
    ): ?Expr {
        $position = $replaceArgumentDefaultValue->getPosition();
        if (! $expr->args[$position] instanceof Arg) {
            return null;
        }

        $argValue = $this->valueResolver->getValue($expr->args[$position]->value);

        if (is_scalar(
            $replaceArgumentDefaultValue->getValueBefore()
        ) && $argValue === $replaceArgumentDefaultValue->getValueBefore()) {
            $expr->args[$position] = $this->normalizeValueToArgument($replaceArgumentDefaultValue->getValueAfter());
        } elseif (is_array($replaceArgumentDefaultValue->getValueBefore())) {
            $newArgs = $this->processArrayReplacement($expr->args, $replaceArgumentDefaultValue);

            if (is_array($newArgs)) {
                $expr->args = $newArgs;
            }
        }

        return $expr;
    }

    /**
     * @param mixed $value
     */
    private function normalizeValueToArgument($value): Arg
    {
        return new Arg($this->normalizeValue($value));
    }

    /**
     * @param mixed $value
     */
    private function normalizeValue($value): ClassConstFetch|Expr
    {
        // class constants → turn string to composite
        if (is_string($value) && \str_contains($value, '::')) {
            [$class, $constant] = explode('::', $value);
            return $this->nodeFactory->createClassConstFetch($class, $constant);
        }

        return BuilderHelpers::normalizeValue($value);
    }

    /**
     * @param Arg[] $argumentNodes
     * @return Arg[]|null
     */
    private function processArrayReplacement(
        array $argumentNodes,
        ReplaceArgumentDefaultValueInterface $replaceArgumentDefaultValue
    ): ?array {
        $argumentValues = $this->resolveArgumentValuesToBeforeRecipe($argumentNodes, $replaceArgumentDefaultValue);
        if ($argumentValues !== $replaceArgumentDefaultValue->getValueBefore()) {
            return null;
        }

        if (is_string($replaceArgumentDefaultValue->getValueAfter())) {
            $argumentNodes[$replaceArgumentDefaultValue->getPosition()] = $this->normalizeValueToArgument(
                $replaceArgumentDefaultValue->getValueAfter()
            );

            // clear following arguments
            $argumentCountToClear = count($replaceArgumentDefaultValue->getValueBefore());
            for ($i = $replaceArgumentDefaultValue->getPosition() + 1; $i <= $replaceArgumentDefaultValue->getPosition() + $argumentCountToClear; ++$i) {
                unset($argumentNodes[$i]);
            }
        }

        return $argumentNodes;
    }

    /**
     * @param Arg[] $argumentNodes
     * @return mixed[]
     */
    private function resolveArgumentValuesToBeforeRecipe(
        array $argumentNodes,
        ReplaceArgumentDefaultValueInterface $replaceArgumentDefaultValue
    ): array {
        $argumentValues = [];

        $valueBefore = $replaceArgumentDefaultValue->getValueBefore();
        if (! is_array($valueBefore)) {
            return [];
        }

        $beforeArgumentCount = count($valueBefore);

        for ($i = 0; $i < $beforeArgumentCount; ++$i) {
            if (! isset($argumentNodes[$replaceArgumentDefaultValue->getPosition() + $i])) {
                continue;
            }

            $nextArg = $argumentNodes[$replaceArgumentDefaultValue->getPosition() + $i];
            $argumentValues[] = $this->valueResolver->getValue($nextArg->value);
        }

        return $argumentValues;
    }
}
