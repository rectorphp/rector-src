<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MissingConstantFromReflectionException;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\AddTypeToConstRectorTest
 */
class AddTypeToConstRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add const to type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public const TYPE = 'some_type';
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public const string TYPE = 'some_type';
}
CODE_SAMPLE
                ,
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    /**
     * @param Node\Stmt\Class_ $node
     */
    public function refactor(Node $node): ?Node\Stmt\Class_
    {
        $consts = array_filter($node->stmts, function (Node $stmt) {
            return $stmt instanceof Node\Stmt\ClassConst;
        });

        if ($consts === []) {
            return null;
        }

        $parentClass = $this->getParentClass($node);

        $changes = false;

        foreach ($consts as $const) {
            // If a type is set, skip
            if ($const->type !== null) {
                continue;
            }

            foreach ($const->consts as $constNode) {
                // If the parent class has the constant then ignore
                if ($parentClass !== null) {
                    try {
                        $parentClass->getConstant($constNode->name->name);
                        continue;
                    } catch (MissingConstantFromReflectionException) {
                    }
                }
                $valueType = $this->findValueType($constNode->value);
            }

            if (($valueType ?? null) === null) {
                continue;
            }

            $const->type = $valueType;

            $changes = true;
        }

        if (! $changes) {
            return null;
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::TYPED_CLASS_CONSTANTS;
    }

    private function findValueType(Node\Expr $value): ?Node\Identifier
    {
        if ($value instanceof Node\Scalar\String_) {
            return new Node\Identifier('string');
        }
        if ($value instanceof Node\Scalar\LNumber) {
            return new Node\Identifier('int');
        }
        if ($value instanceof Node\Scalar\DNumber) {
            return new Node\Identifier('float');
        }
        if ($value instanceof Node\Expr\ConstFetch && $value->name->toLowerString() !== 'null') {
            return new Node\Identifier('bool');
        }
        if ($value instanceof Node\Expr\ConstFetch && $value->name->toLowerString() === 'null') {
            return new Node\Identifier('null');
        }
        if ($value instanceof Node\Expr\Array_) {
            return new Node\Identifier('array');
        }

        return null;
    }

    private function getParentClass(Class_ $class): ?ClassReflection
    {
        if (! $class->extends instanceof FullyQualified) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($class->extends->toString())) {
            return null;
        }

        return $this->reflectionProvider->getClass($class->extends->toString());
    }
}
