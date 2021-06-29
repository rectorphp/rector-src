<?php

declare(strict_types=1);

namespace Rector\Arguments;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Arguments\Contract\ReplaceArgumentDefaultValueInterface;
use Rector\Core\PhpParser\Node\Value\ValueResolver;

final class ArgumentDefaultValueReplacer
{
    public function __construct(
        private ValueResolver $valueResolver
    ) {
    }

    public function processReplaces(
        MethodCall | StaticCall | ClassMethod | Expr\FuncCall $node,
        ReplaceArgumentDefaultValueInterface $replaceArgumentDefaultValue
    ): ?Node {
        if ($node instanceof ClassMethod) {
            if (! isset($node->params[$replaceArgumentDefaultValue->getPosition()])) {
                return null;
            }
        } elseif (isset($node->args[$replaceArgumentDefaultValue->getPosition()])) {
            $this->processArgs($node, $replaceArgumentDefaultValue);
        }

        return $node;
    }

    private function processArgs(
        MethodCall | StaticCall | FuncCall $expr,
        ReplaceArgumentDefaultValueInterface $replaceArgumentDefaultValue
    ): void {
        $position = $replaceArgumentDefaultValue->getPosition();

        $argValue = $this->valueResolver->getValue($expr->args[$position]->value);

        if ($this->valueResolver->areValuesEqual($argValue, $replaceArgumentDefaultValue->getValueBefore())) {
            $expr->args[$position] = new Arg($replaceArgumentDefaultValue->getValueAfter());
        }
    }
}
