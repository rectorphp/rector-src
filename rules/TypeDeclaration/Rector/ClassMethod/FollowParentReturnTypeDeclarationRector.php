<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\FollowParentReturnTypeDeclarationRector\FollowParentReturnTypeDeclarationRectorTest
 */
final class FollowParentReturnTypeDeclarationRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(private readonly AstResolver $astResolver)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Copy return type declaration from parent method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
abstract class ParentClass
{
    public abstract function getData(): array;
}

class ChildClass extends ParentClass
{
    public function getData()
    {
        return [];
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
abstract class ParentClass
{
    public abstract function getData(): array;
}

class ChildClass extends ParentClass
{
    public function getData(): array
    {
        return [];
    }
}
CODE_SAMPLE

            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class, Interface_::class];
    }

    /**
     * @param Class_|Interface_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // a class, no extends/implements, skip
        if ($node instanceof Class_ && ! $node->extends instanceof FullyQualified && $node->implements === []) {
            return null;
        }

        // an interface, no extends, skip
        if ($node instanceof Interface_ && $node->extends === []) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->getMethods() as $classMethod) {
            // private scope is only local
            if ($classMethod->isPrivate()) {
                continue;
            }

            // __construct don't have return type
            if ($this->isName($classMethod, MethodName::CONSTRUCT)) {
                continue;
            }

            // already return typed
            if ($classMethod->returnType instanceof Node) {
                continue;
            }

            $parentReturnType = $this->resolveParentReturnType($node, $classMethod);
            if (! $parentReturnType instanceof Node) {
                continue;
            }

            $classMethod->returnType = $parentReturnType;
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function resolveParentReturnType(Class_|Interface_ $node, ClassMethod $classMethod): ?Node
    {
        $classMethodName = $classMethod->name->toString();

        if ($node instanceof Class_ && $node->extends instanceof FullyQualified) {
            $parentClass = $this->astResolver->resolveClassFromName($node->extends->toString());
            if ($parentClass instanceof Class_) {
                foreach ($parentClass->getMethods() as $method) {
                    if ($method->returnType instanceof Node && $method->name->toString() === $classMethodName) {
                        return $method->returnType;
                    }
                }
            }
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }
}
