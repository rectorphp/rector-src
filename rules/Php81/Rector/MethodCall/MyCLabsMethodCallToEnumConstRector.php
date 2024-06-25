<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\MyCLabsMethodCallToEnumConstRectorTest
 */
final class MyCLabsMethodCallToEnumConstRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string[]
     */
    private const ENUM_METHODS = ['from', 'values', 'keys', 'isValid', 'search', 'toArray', 'assertValidValue'];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor MyCLabs enum fetch to Enum const', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$name = SomeEnum::VALUE()->getKey();
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
$name = SomeEnum::VALUE;
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->name instanceof Expr) {
            return null;
        }

        $enumCaseName = $this->getName($node->name);
        if ($enumCaseName === null) {
            return null;
        }

        if ($this->shouldOmitEnumCase($enumCaseName)) {
            return null;
        }

        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node, $enumCaseName);
        }

        if (! $this->isObjectType($node->class, new ObjectType('MyCLabs\Enum\Enum'))) {
            return null;
        }

        $className = $this->getName($node->class);
        if (! is_string($className)) {
            return null;
        }

        if (! $this->isEnumConstant($className, $enumCaseName)) {
            return null;
        }

        return $this->nodeFactory->createClassConstFetch($className, $enumCaseName);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }

    private function isEnumConstant(string $className, string $constant): bool
    {
        $classReflection = $this->reflectionProvider->getClass($className);

        return $classReflection->hasConstant($constant);
    }

    private function refactorGetKeyMethodCall(MethodCall $methodCall): ?ClassConstFetch
    {
        if (! $methodCall->var instanceof StaticCall) {
            return null;
        }

        $staticCall = $methodCall->var;
        $className = $this->getName($staticCall->class);
        if ($className === null) {
            return null;
        }

        $enumCaseName = $this->getName($staticCall->name);
        if ($enumCaseName === null) {
            return null;
        }

        if ($this->shouldOmitEnumCase($enumCaseName)) {
            return null;
        }

        return $this->nodeFactory->createClassConstFetch($className, $enumCaseName);
    }

    private function refactorGetValueMethodCall(MethodCall $methodCall): ?PropertyFetch
    {
        if (! $methodCall->var instanceof StaticCall) {
            return null;
        }

        $staticCall = $methodCall->var;
        $className = $this->getName($staticCall->class);
        if ($className === null) {
            return null;
        }

        $enumCaseName = $this->getName($staticCall->name);
        if ($enumCaseName === null) {
            return null;
        }

        if ($this->shouldOmitEnumCase($enumCaseName)) {
            return null;
        }

        $classConstFetch = $this->nodeFactory->createClassConstFetch($className, $enumCaseName);

        return new PropertyFetch($classConstFetch, 'value');
    }

    private function refactorEqualsMethodCall(MethodCall $methodCall): ?Identical
    {
        $expr = $this->getNonEnumReturnTypeExpr($methodCall->var);
        if (! $expr instanceof Expr) {
            $expr = $this->getValidEnumExpr($methodCall->var);
            if (! $expr instanceof Expr) {
                return null;
            }
        }

        $arg = $methodCall->getArgs()[0] ?? null;
        if (! $arg instanceof Arg) {
            return null;
        }

        $right = $this->getNonEnumReturnTypeExpr($arg->value);
        if (! $right instanceof Expr) {
            $right = $this->getValidEnumExpr($arg->value);
            if (! $right instanceof Expr) {
                return null;
            }
        }

        return new Identical($expr, $right);
    }

    private function isCallerClassEnum(StaticCall|MethodCall $node): bool
    {
        if ($node instanceof StaticCall) {
            return $this->isObjectType($node->class, new ObjectType('MyCLabs\Enum\Enum'));
        }

        return $this->isObjectType($node->var, new ObjectType('MyCLabs\Enum\Enum'));
    }

    private function getNonEnumReturnTypeExpr(Node $node): null|ClassConstFetch|Expr
    {
        if (! $node instanceof StaticCall && ! $node instanceof MethodCall) {
            return null;
        }

        if ($this->isCallerClassEnum($node)) {
            $methodName = $this->getName($node->name);
            if ($methodName === null) {
                return null;
            }

            if ($node instanceof StaticCall) {
                $className = $this->getName($node->class);
            }

            if ($node instanceof MethodCall) {
                $className = $this->getName($node->var);
            }

            if ($className === null) {
                return null;
            }

            $classReflection = $this->reflectionProvider->getClass($className);
            // method self::getValidEnumExpr process enum static methods from constants
            if ($classReflection->hasConstant($methodName)) {
                return null;
            }
        }

        return $node;
    }

    private function getValidEnumExpr(Node $node): null|ClassConstFetch|Expr
    {
        return match ($node::class) {
            Variable::class, PropertyFetch::class => $this->getPropertyFetchOrVariable($node),
            StaticCall::class => $this->getEnumConstFetch($node),
            default => null,
        };
    }

    private function getPropertyFetchOrVariable(PropertyFetch|Variable $expr): null|PropertyFetch|Variable
    {
        if (! $this->isObjectType($expr, new ObjectType('MyCLabs\Enum\Enum'))) {
            return null;
        }

        return $expr;
    }

    private function getEnumConstFetch(StaticCall $staticCall): null|ClassConstFetch
    {
        $className = $this->getName($staticCall->class);
        if ($className === null) {
            return null;
        }

        $enumCaseName = $this->getName($staticCall->name);
        if ($enumCaseName === null) {
            return null;
        }

        if ($this->shouldOmitEnumCase($enumCaseName)) {
            return null;
        }

        return $this->nodeFactory->createClassConstFetch($className, $enumCaseName);
    }

    private function refactorMethodCall(
        MethodCall $methodCall,
        string $methodName
    ): null|ClassConstFetch|PropertyFetch|Identical {
        if (! $this->isObjectType($methodCall->var, new ObjectType('MyCLabs\Enum\Enum'))) {
            return null;
        }

        if ($methodName === 'getKey') {
            return $this->refactorGetKeyMethodCall($methodCall);
        }

        if ($methodName === 'getValue') {
            return $this->refactorGetValueMethodCall($methodCall);
        }

        if ($methodName === 'equals') {
            return $this->refactorEqualsMethodCall($methodCall);
        }

        return null;
    }

    private function shouldOmitEnumCase(string $enumCaseName): bool
    {
        return in_array($enumCaseName, self::ENUM_METHODS, true);
    }
}
