<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
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
        return [Class_::class, Interface_::class, Trait_::class];
    }

    /**
     * @param Class_|Interface_|Trait_ $node
     */
    public function refactor(Node $node): Class_|Interface_|Trait_|null
    {
        $consts = array_filter($node->stmts, function (Node $stmt) {
            return $stmt instanceof Node\Stmt\ClassConst;
        });

        if ($consts === []) {
            return null;
        }

        $parentClass = null;
        if ($node instanceof Class_) {
            $parentClass = $this->getParentClass($node);
        }
        /** @var ClassReflection[] $implementations */
        $implementations = [];
        if ($node instanceof Class_) {
            $implementations = $this->getImplementations($node);
        }

        $changes = false;

        foreach ($consts as $const) {
            // If a type is set, skip
            if ($const->type !== null) {
                continue;
            }

            foreach ($const->consts as $constNode) {
                if ($this->shouldSkipDueToInheritance($parentClass, $constNode, $implementations)) {
                    continue;
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

    /**
     * @param ClassReflection[] $implementations
     */
    public function shouldSkipDueToInheritance(
        ?ClassReflection $parentClass,
        Node\Const_ $constNode,
        array $implementations
    ): bool {
        // If the parent class has the constant then ignore
        if ($parentClass !== null) {
            try {
                $parentClass->getConstant($constNode->name->name);
                return true;
            } catch (MissingConstantFromReflectionException) {
            }
        }
        foreach ($implementations as $implementation) {
            if ($constNode->name->name === '') {
                continue;
            }
            try {
                $implementation->getConstant($constNode->name->name);
                return true;
            } catch (MissingConstantFromReflectionException) {
            }
        }

        return false;
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

    /**
     * @return ClassReflection[]
     */
    private function getImplementations(Class_ $class): array
    {
        return array_filter(array_map(function (Node\Name $name) {
            if ($this->reflectionProvider->hasClass($name->toString())) {
                return $this->reflectionProvider->getClass($name->toString());
            }

            return null;
        }, $class->implements));
    }
}
