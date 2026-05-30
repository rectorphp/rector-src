<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\MethodCall;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\RenameDeprecatedMethodCallRectorTest
 */
final class RenameDeprecatedMethodCallRector extends AbstractRector
{
    /**
     * Matches the new method name suggested inside a "@deprecated" description, e.g.:
     *  - "use newMethod() instead"
     *  - "replaced by newMethod()"
     *  - "{@see newMethod()}"
     *
     * Cross-class suggestions ("Other::newMethod()") are intentionally skipped for now.
     */
    private const string RENAME_SUGGESTION_REGEX = '#(?:\buse\b|\breplaced by\b|\{@see)\s+(?<method>\w+)\(\)#i';

    public function __construct(
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename method calls whose target method is "@deprecated" and suggests a replacement method on the same class',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$someObject->oldMethod();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$someObject->newMethod();
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
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        $callName = $this->getName($node->name);
        if ($callName === null) {
            return null;
        }

        $methodReflection = $node instanceof MethodCall
            ? $this->reflectionResolver->resolveMethodReflectionFromMethodCall($node)
            : $this->reflectionResolver->resolveMethodReflectionFromStaticCall($node);

        if (! $methodReflection instanceof MethodReflection) {
            return null;
        }

        if (! $methodReflection->isDeprecated()->yes()) {
            return null;
        }

        $newMethodName = $this->matchNewMethodName($methodReflection->getDeprecatedDescription());
        if ($newMethodName === null) {
            return null;
        }

        // already the suggested name? nothing to do
        if ($this->nodeNameResolver->isStringName($callName, $newMethodName)) {
            return null;
        }

        if (! $this->isExistingNonDeprecatedMethod($methodReflection->getDeclaringClass(), $newMethodName)) {
            return null;
        }

        $node->name = new Identifier($newMethodName);

        return $node;
    }

    private function matchNewMethodName(?string $deprecatedDescription): ?string
    {
        if ($deprecatedDescription === null || $deprecatedDescription === '') {
            return null;
        }

        $match = Strings::match($deprecatedDescription, self::RENAME_SUGGESTION_REGEX);
        if ($match === null) {
            return null;
        }

        return $match['method'];
    }

    private function isExistingNonDeprecatedMethod(ClassReflection $classReflection, string $newMethodName): bool
    {
        if (! $classReflection->hasMethod($newMethodName)) {
            return false;
        }

        // do not rename onto another deprecated method, to avoid suggesting a dead end
        $extendedMethodReflection = $classReflection->getNativeMethod($newMethodName);
        return ! $extendedMethodReflection->isDeprecated()
            ->yes();
    }
}
