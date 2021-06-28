<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Array_;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPStan\Reflection\Php\PhpMethodReflection;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeCollector\NodeAnalyzer\ArrayCallableMethodReferenceAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php72\NodeFactory\AnonymousFunctionFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/language.types.callable.php#117260
 * @see https://3v4l.org/MsMbQ
 * @see https://3v4l.org/KM1Ji
 *
 * @see \Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\CallableThisArrayToAnonymousFunctionRectorTest
 */
final class CallableThisArrayToAnonymousFunctionRector extends AbstractRector
{
    public function __construct(
        private AnonymousFunctionFactory $anonymousFunctionFactory,
        private ReflectionResolver $reflectionResolver,
        private ArrayCallableMethodReferenceAnalyzer $arrayCallableMethodReferenceAnalyzer
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
    public function refactor(Node $node): ?Node
    {
        $arrayCallable = $this->arrayCallableMethodReferenceAnalyzer->match($node);
        if ($arrayCallable === null) {
            return null;
        }

        /** @var Array_ $node */
        $objectVariable = $node->items[0]->value;

//        if ($this->shouldSkipArray($node)) {
//            return null;
//        }

//        $firstArrayItem = $node->items[0];
//        if (! $firstArrayItem instanceof ArrayItem) {
//            return null;
//        }

//        $objectVariable = $firstArrayItem->value;
//        if (! $objectVariable instanceof Variable && ! $objectVariable instanceof PropertyFetch) {
//            return null;
//        }
//
//        $secondArrayItem = $node->items[1];
//        if (! $secondArrayItem instanceof ArrayItem) {
//            return null;
//        }

//        $methodName = $secondArrayItem->value;
//        if (! $methodName instanceof String_) {
//            return null;
//        }

//        dump($arrayCallable->getClass());
//        dump($arrayCallable->getMethod());

        $scope = $node->getAttribute(AttributeKey::SCOPE);

        $phpMethodReflection = $this->reflectionResolver->resolveMethodReflection(
            $arrayCallable->getClass(),
            $arrayCallable->getMethod(),
            $scope
        );

//        if ($phpMethodReflection === null) {
//
//        $phpMethodReflection = $this->callableClassMethodMatcher->match($objectVariable, $methodName);
        if (! $phpMethodReflection instanceof PhpMethodReflection) {
            return null;
        }

        return $this->anonymousFunctionFactory->createFromPhpMethodReflection($phpMethodReflection, $objectVariable);
    }
}
