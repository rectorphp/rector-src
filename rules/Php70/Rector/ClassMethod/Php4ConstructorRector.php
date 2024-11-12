<?php

declare(strict_types=1);

namespace Rector\Php70\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Enum\ObjectReference;
use Rector\NodeCollector\ScopeResolver\ParentClassScopeResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php70\NodeAnalyzer\Php4ConstructorClassMethodAnalyzer;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php70\Rector\ClassMethod\Php4ConstructorRector\Php4ConstructorRectorTest
 */
final class Php4ConstructorRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly Php4ConstructorClassMethodAnalyzer $php4ConstructorClassMethodAnalyzer,
        private readonly ParentClassScopeResolver $parentClassScopeResolver
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NO_PHP4_CONSTRUCTOR;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes PHP 4 style constructor to __construct.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function SomeClass()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct()
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
    public function refactor(Node $node): Class_|int|null
    {
        $className = $this->getName($node);
        if (! is_string($className)) {
            return null;
        }

        $psr4ConstructorMethod = $node->getMethod(lcfirst($className)) ?? $node->getMethod($className);
        if (! $psr4ConstructorMethod instanceof ClassMethod) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        if (! $this->php4ConstructorClassMethodAnalyzer->detect($psr4ConstructorMethod, $scope)) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        // process parent call references first
        $this->processClassMethodStatementsForParentConstructorCalls($psr4ConstructorMethod, $scope);

        // does it already have a __construct method?
        if (! $classReflection->hasNativeMethod(MethodName::CONSTRUCT)) {
            $psr4ConstructorMethod->name = new Identifier(MethodName::CONSTRUCT);
        }

        $classMethodStmts = $psr4ConstructorMethod->stmts;
        if ($classMethodStmts === null) {
            return null;
        }

        if (count($classMethodStmts) === 1) {
            $stmt = $psr4ConstructorMethod->stmts[0];
            if (! $stmt instanceof Expression) {
                return null;
            }

            if ($this->isLocalMethodCallNamed($stmt->expr, MethodName::CONSTRUCT)) {
                $stmtKey = $psr4ConstructorMethod->getAttribute(AttributeKey::STMT_KEY);
                unset($node->stmts[$stmtKey]);
            }
        }

        return $node;
    }

    private function processClassMethodStatementsForParentConstructorCalls(ClassMethod $classMethod, Scope $scope): void
    {
        if (! is_iterable($classMethod->stmts)) {
            return;
        }

        foreach ($classMethod->stmts as $methodStmt) {
            if (! $methodStmt instanceof Expression) {
                continue;
            }

            $methodStmt = $methodStmt->expr;
            if (! $methodStmt instanceof StaticCall) {
                continue;
            }

            $this->processParentPhp4ConstructCall($methodStmt, $scope);
        }
    }

    private function processParentPhp4ConstructCall(StaticCall $staticCall, Scope $scope): void
    {
        $parentClassReflection = $this->parentClassScopeResolver->resolveParentClassReflection($scope);

        // no parent class
        if (! $parentClassReflection instanceof ClassReflection) {
            return;
        }

        if (! $staticCall->class instanceof Name) {
            return;
        }

        // rename ParentClass
        if ($this->isName($staticCall->class, $parentClassReflection->getName())) {
            $staticCall->class = new Name(ObjectReference::PARENT);
        }

        if (! $this->isName($staticCall->class, ObjectReference::PARENT)) {
            return;
        }

        // it's not a parent PHP 4 constructor call
        if (! $this->isName($staticCall->name, $parentClassReflection->getName())) {
            return;
        }

        $staticCall->name = new Identifier(MethodName::CONSTRUCT);
    }

    private function isLocalMethodCallNamed(Expr $expr, string $name): bool
    {
        if (! $expr instanceof MethodCall) {
            return false;
        }

        if ($expr->var instanceof StaticCall) {
            return false;
        }

        if ($expr->var instanceof MethodCall) {
            return false;
        }

        if (! $this->isName($expr->var, 'this')) {
            return false;
        }

        return $this->isName($expr->name, $name);
    }
}
