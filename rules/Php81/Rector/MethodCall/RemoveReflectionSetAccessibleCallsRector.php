<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitor;
use Rector\Analyzer\SimpleScope\SimpleScope;
use Rector\Analyzer\SimpleScope\SimpleScopeResolver;
use Rector\Analyzer\SimpleType\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * As of PHP 8.1.0, calling `Reflection*::setAccessible()` has no effect.
 *
 * Type resolution is done via the PHPStan-free SimpleScope.
 *
 * @see https://www.php.net/manual/en/reflectionmethod.setaccessible.php
 * @see https://www.php.net/manual/en/reflectionproperty.setaccessible.php
 * @see \Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\RemoveReflectionSetAccessibleCallsRectorTest
 */
final class RemoveReflectionSetAccessibleCallsRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string[]
     */
    private const array REFLECTION_CLASSES = ['ReflectionProperty', 'ReflectionMethod'];

    public function __construct(
        private readonly SimpleScopeResolver $simpleScopeResolver
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        $stmts = $node->stmts;
        if ($stmts === null) {
            return null;
        }

        $simpleScope = $this->simpleScopeResolver->resolve([$node]);

        $hasChanged = false;
        $this->traverseNodesWithCallable($node, function (Node $subNode) use ($simpleScope, &$hasChanged): ?int {
            if (! $this->isReflectionSetAccessibleCall($subNode, $simpleScope)) {
                return null;
            }

            $hasChanged = true;
            return NodeVisitor::REMOVE_NODE;
        });

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove Reflection::setAccessible() calls', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$reflectionProperty = new ReflectionProperty($object, 'property');
$reflectionProperty->setAccessible(true);
$value = $reflectionProperty->getValue($object);

$reflectionMethod = new ReflectionMethod($object, 'method');
$reflectionMethod->setAccessible(false);
$reflectionMethod->invoke($object);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$reflectionProperty = new ReflectionProperty($object, 'property');
$value = $reflectionProperty->getValue($object);

$reflectionMethod = new ReflectionMethod($object, 'method');
$reflectionMethod->invoke($object);
CODE_SAMPLE
            ),
        ]);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_81;
    }

    private function isReflectionSetAccessibleCall(Node $node, SimpleScope $simpleScope): bool
    {
        if (! $node instanceof Expression) {
            return false;
        }

        if (! $node->expr instanceof MethodCall) {
            return false;
        }

        $methodCall = $node->expr;
        if (! $this->isName($methodCall->name, 'setAccessible')) {
            return false;
        }

        $simpleType = $simpleScope->getType($methodCall->var);
        if (! $simpleType instanceof ObjectType) {
            return false;
        }

        return in_array($simpleType->getClassName(), self::REFLECTION_CLASSES, true);
    }
}
