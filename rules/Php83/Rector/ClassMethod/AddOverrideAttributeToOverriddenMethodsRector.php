<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpParser\AstResolver;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/marking_overriden_methods
 *
 * @see \Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\AddOverrideAttributeToOverriddenMethodsRectorTest
 */
final class AddOverrideAttributeToOverriddenMethodsRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const OVERRIDE_CLASS = 'Override';

    private bool $hasChanged = false;

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly AstResolver $astResolver,
        private readonly ValueResolver $valueResolver,
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
        echo 'default';
    }
}

final class ChildClass extends ParentClass
{
    public function foo()
    {
        echo 'override default';
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class ParentClass
{
    public function foo()
    {
        echo 'default';
    }
}

final class ChildClass extends ParentClass
{
    #[\Override]
    public function foo()
    {
        echo 'override default';
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
    public function refactor(Node $node): ?Node
    {
        $this->hasChanged = false;

        if ($this->classAnalyzer->isAnonymousClass($node)) {
            return null;
        }

        $className = (string) $this->getName($node);
        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        $parentClassReflections = $classReflection->getParents();

        if ($parentClassReflections === []) {
            return null;
        }

        foreach ($node->getMethods() as $classMethod) {
            $this->processAddOverrideAttribute($classMethod, $parentClassReflections);
        }

        if (! $this->hasChanged) {
            return null;
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::OVERRIDE_ATTRIBUTE;
    }

    /**
     * @param ClassReflection[] $parentClassReflections
     */
    private function processAddOverrideAttribute(ClassMethod $classMethod, array $parentClassReflections): void
    {
        if ($this->shouldSkipClassMethod($classMethod)) {
            return;
        }

        /** @var string $classMethodName */
        $classMethodName = $this->getName($classMethod->name);

        // Private methods should be ignored
        $shouldAddOverride = false;
        foreach ($parentClassReflections as $parentClassReflection) {
            if (! $parentClassReflection->hasNativeMethod($classMethod->name->toString())) {
                continue;
            }

            // ignore if it is a private method on the parent
            if (! $parentClassReflection->hasNativeMethod($classMethodName)) {
                continue;
            }

            $parentMethod = $parentClassReflection->getNativeMethod($classMethodName);
            if ($parentMethod->isPrivate()) {
                break;
            }

            if ($this->shouldSkipParentClassMethod($parentClassReflection, $classMethod)) {
                continue;
            }

            $shouldAddOverride = true;
            break;
        }

        if ($shouldAddOverride) {
            $classMethod->attrGroups[] = new AttributeGroup([new Attribute(new FullyQualified(self::OVERRIDE_CLASS))]);
            $this->hasChanged = true;
        }
    }

    private function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        if ($this->isName($classMethod->name, MethodName::CONSTRUCT)) {
            return true;
        }

        if ($classMethod->isPrivate()) {
            return true;
        }

        // ignore if it already uses the attribute
        return $this->phpAttributeAnalyzer->hasPhpAttribute($classMethod, self::OVERRIDE_CLASS);
    }

    private function shouldSkipParentClassMethod(ClassReflection $parentClassReflection, ClassMethod $classMethod): bool
    {
        // parse parent method, if it has some contents or not
        $parentClass = $this->astResolver->resolveClassFromClassReflection($parentClassReflection);
        if (! $parentClass instanceof ClassLike) {
            return true;
        }

        $parentClassMethod = $parentClass->getMethod($classMethod->name->toString());
        if (! $parentClassMethod instanceof ClassMethod) {
            return true;
        }

        // just override abstract method also skipped on purpose
        // only grand child of abstract method that parent has content will have
        if ($parentClassMethod->isAbstract()) {
            return true;
        }

        // has any stmts?
        if ($parentClassMethod->stmts === null || $parentClassMethod->stmts === []) {
            return true;
        }

        if (count($parentClassMethod->stmts) === 1) {
            /** @var Stmt $soleStmt */
            $soleStmt = $parentClassMethod->stmts[0];
            // most likely, return null; is interface to be designed to override
            if ($soleStmt instanceof Return_ && $soleStmt->expr instanceof Expr && $this->valueResolver->isNull(
                $soleStmt->expr
            )) {
                return true;
            }

            if ($soleStmt instanceof Expression && $soleStmt->expr instanceof Throw_) {
                return true;
            }
        }

        return false;
    }
}
