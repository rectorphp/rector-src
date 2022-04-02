<?php

declare(strict_types=1);

namespace Rector\DowngradePhp54\Rector\Closure;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/closures/object-extension
 *
 * @see \Rector\Tests\DowngradePhp54\Rector\Closure\DowngradeThisInClosureRector\DowngradeThisInClosureRectorTest
 */
final class DowngradeThisInClosureRector extends AbstractRector
{
    public function __construct(
        private readonly VariableNaming $variableNaming,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Downgrade $this-> inside to use assigned $self = $this before Closure', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public $property = 'test';

    public function run()
    {
        $function = function () {
            echo $this->property;
        };

        $function();
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public $property = 'test';

    public function run()
    {
        $self = $this;
        $function = function () use ($self) {
            echo $self->property;
        };

        $function();
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
        return [Closure::class];
    }

    /**
     * @param Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        $closureParentFunctionLike = $this->betterNodeFinder->findParentByTypes(
            $node,
            [ClassMethod::class, Function_::class]
        );

        /** @var PropertyFetch[] $propertyFetches */
        $propertyFetches = $this->betterNodeFinder->find($node->stmts, function (Node $subNode) use (
            $closureParentFunctionLike
        ): bool {
            // multiple deep Closure may access $this, unless its parent is not Closure
            $parent = $this->betterNodeFinder->findParentByTypes($subNode, [ClassMethod::class, Function_::class]);

            if ($parent instanceof FunctionLike && $parent !== $closureParentFunctionLike) {
                return false;
            }

            if (! $subNode instanceof PropertyFetch) {
                return false;
            }

            if (! $this->nodeNameResolver->isName($subNode->var, 'this')) {
                return false;
            }

            $phpPropertyReflection = $this->reflectionResolver->resolvePropertyReflectionFromPropertyFetch($subNode);
            if (! $phpPropertyReflection instanceof PhpPropertyReflection) {
                return false;
            }

            return $phpPropertyReflection->isPublic();
        });

        if ($propertyFetches === []) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        $selfVariable = new Variable($this->variableNaming->createCountedValueName('self', $scope));
        $expression = new Expression(new Assign($selfVariable, new Variable('this')));

        $currentStmt = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $this->nodesToAddCollector->addNodeBeforeNode($expression, $currentStmt);

        $this->traverseNodesWithCallable($node, function (Node $subNode) use ($selfVariable): ?Closure {
            if (! $subNode instanceof Closure) {
                return null;
            }

            $subNode->uses = array_merge($subNode->uses, [new ClosureUse($selfVariable)]);
            return $subNode;
        });

        foreach ($propertyFetches as $propertyFetch) {
            $propertyFetch->var = $selfVariable;
        }

        return $node;
    }
}
