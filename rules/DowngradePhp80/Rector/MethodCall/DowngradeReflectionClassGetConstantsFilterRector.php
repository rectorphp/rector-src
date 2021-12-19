<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BitwiseOr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp80\Rector\MethodCall\DowngradeReflectionClassGetConstantsFilterRector\DowngradeReflectionClassGetConstantsFilterRectorTest
 */
final class DowngradeReflectionClassGetConstantsFilterRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Downgrade ReflectionClass->getConstants(ReflectionClassConstant::IS_*)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$reflectionClass = new ReflectionClass('SomeClass');
$constants = $reflectionClass->getConstants(ReflectionClassConstant::IS_PUBLIC));
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$reflectionClass = new ReflectionClass('SomeClass');
$reflectionClassConstants = $reflectionClass->getReflectionConstants();
$result = [];
array_walk($reflectionClassConstants, function ($value) use (&$result) {
    if ($value->isPublic()) {
       $result[$value->getName()] = $value->getValue();
    }
});
$constants = $result;
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var Arg[] $args */
        $args = $node->getArgs();
        $value = $args[0]->value;

        if (! in_array($value::class, [ClassConstFetch::class, BitwiseOr::class], true)) {
            return null;
        }

        if ($value instanceof ClassConstFetch) {
            if ($this->shouldSkipClassConstFetch($value)) {
                return null;
            }
        }

        if ($value instanceof BitwiseOr && $value->right instanceof ClassConstFetch) {
            $values[] = $value->right;

            if ($value->left instanceof BitwiseOr) {
                $values[] = $value->left->right;
                $values[] = $value->left->left;
            } else {
                $values[] = $value->left;
            }

            ksort($values);

            if ($this->shouldSkipBitwiseOrValues($values)) {
                return null;
            }
        }

        return $node;
    }

    /**
     * @param Node[] $values
     */
    private function shouldSkipBitwiseOrValues(array $values): bool
    {
        foreach ($values as $value) {
            if (! $value instanceof ClassConstFetch) {
                return true;
            }

            if ($this->shouldSkipClassConstFetch($value)) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkipClassConstFetch(ClassConstFetch $classConstFetch): bool
    {
        if (! $classConstFetch->class instanceof FullyQualified) {
            return true;
        }

        if (! $classConstFetch->name instanceof Identifier) {
            return true;
        }

        return ! $this->nodeNameResolver->isNames($classConstFetch->name, ['IS_PUBLIC', 'IS_PROTECTED', 'IS_PRIVATE'], true);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->nodeNameResolver->isName($methodCall->name, 'getConstants')) {
            return true;
        }

        $varType = $this->nodeTypeResolver->getType($methodCall->var);
        if (! $varType instanceof ObjectType) {
            return true;
        }

        if ($varType->getClassName() !== 'ReflectionClass') {
            return true;
        }

        return $methodCall->getArgs() === [];
    }
}
