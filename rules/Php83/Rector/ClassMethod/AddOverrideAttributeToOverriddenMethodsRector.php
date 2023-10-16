<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/marking_overriden_methods
 * @see \Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\AddOverrideAttributeToOverriddenMethodsRectorTest
 */
class AddOverrideAttributeToOverriddenMethodsRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassAnalyzer $classAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add override attribute to overridden methods',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class ParentClass
{
    public function foo()
    {
    }
}

class ChildClass extends ParentClass
{
    public function foo()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class ParentClass
{
    public function foo()
    {
    }
}

class ChildClass extends ParentClass
{
    #[\Override]
    public function foo()
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        // Detect if class extends a parent class
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        // Fetch the parent class reflection
        $parentClassReflection = $this->reflectionProvider->getClass((string) $node->extends);

        foreach ($node->getMethods() as $method) {
            if ($method->name->toString() === '__construct') {
                continue;
            }
            // Private methods should be ignored
            if ($parentClassReflection->hasMethod($method->name->toString())) {
                // ignore if it is a private method on the parent
                $parentMethod = $parentClassReflection->getMethod($method->name->toString(), $scope);
                if ($parentMethod->isPrivate()) {
                    continue;
                }
                // ignore if it already uses the attribute
                if ($this->hasPhpAttribute($method)) {
                    continue;
                }
                $method->attrGroups[] = new AttributeGroup([new Attribute(new FullyQualified('Override'))]);
            }
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::OVERRIDE_ATTRIBUTE;
    }

    private function hasPhpAttribute(ClassMethod $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if (! $this->nodeNameResolver->isName($attribute->name, 'Override')) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return true;
        }

        return $class->extends instanceof FullyQualified && ! $this->reflectionProvider->hasClass(
            $class->extends->toString()
        );
    }
}
