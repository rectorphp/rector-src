<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Array_;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Php\PhpMethodReflection;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeCollector\NodeAnalyzer\ArrayCallableMethodMatcher;
use Rector\NodeCollector\ValueObject\ArrayCallable;
use Rector\Php72\NodeFactory\AnonymousFunctionFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/language.types.callable.php#117260
 * @changelog https://3v4l.org/MsMbQ
 * @changelog https://3v4l.org/KM1Ji
 *
 * @see \Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\CallableThisArrayToAnonymousFunctionRectorTest
 */
final class CallableThisArrayToAnonymousFunctionRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly AnonymousFunctionFactory $anonymousFunctionFactory,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ArrayCallableMethodMatcher $arrayCallableMethodMatcher,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert [$this, "method"] to proper anonymous function',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $values = [1, 5, 3];
        usort($values, [$this, 'compareSize']);

        return $values;
    }

    private function compareSize($first, $second)
    {
        return $first <=> $second;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $values = [1, 5, 3];
        usort($values, function ($first, $second) {
            return $this->compareSize($first, $second);
        });

        return $values;
    }

    private function compareSize($first, $second)
    {
        return $first <=> $second;
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
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        $arrayCallable = $this->arrayCallableMethodMatcher->match($node, $scope);
        if (! $arrayCallable instanceof ArrayCallable) {
            return null;
        }

        $phpMethodReflection = $this->reflectionResolver->resolveMethodReflection(
            $arrayCallable->getClass(),
            $arrayCallable->getMethod(),
            $scope
        );

        if (! $phpMethodReflection instanceof PhpMethodReflection) {
            return null;
        }

        return $this->anonymousFunctionFactory->createFromPhpMethodReflection(
            $phpMethodReflection,
            $arrayCallable->getCallerExpr()
        );
    }
}
