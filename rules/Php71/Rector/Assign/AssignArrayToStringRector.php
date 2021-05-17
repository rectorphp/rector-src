<?php

declare(strict_types=1);

namespace Rector\Php71\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Cast\Array_ as ArrayCast;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use Rector\Core\Rector\AbstractRector;
use Rector\Php71\NodeFinder\EmptyStringDefaultPropertyFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://stackoverflow.com/a/41000866/1348344 https://3v4l.org/ABDNv
 *
 * @see \Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\AssignArrayToStringRectorTest
 */
final class AssignArrayToStringRector extends AbstractRector
{
    /**
     * @var PropertyProperty[]
     */
    private array $emptyStringProperties = [];

    public function __construct(
        private EmptyStringDefaultPropertyFinder $emptyStringDefaultPropertyFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'String cannot be turned into array by assignment anymore',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$string = '';
$string[] = 1;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$string = [];
$string[] = 1;
CODE_SAMPLE
            )]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->emptyStringProperties = $this->emptyStringDefaultPropertyFinder->find($node);

        // only array with no explicit key assign, e.g. "$value[] = 5";
        if (! $node->var instanceof ArrayDimFetch) {
            return null;
        }

        if ($node->var->dim !== null) {
            return null;
        }

        $arrayDimFetchNode = $node->var;

        /** @var Variable|PropertyFetch|StaticPropertyFetch|Expr $variable */
        $variable = $arrayDimFetchNode->var;

        // set default value to property
        if (($variable instanceof PropertyFetch || $variable instanceof StaticPropertyFetch) &&
            $this->refactorPropertyFetch($variable)
        ) {
            return $node;
        }

        // fallback to variable, property or static property = '' set
        if ($this->processVariable($node, $variable)) {
            return $node;
        }

        $isFoundPrev = (bool) $this->betterNodeFinder->findFirstPreviousOfNode(
            $variable,
            fn (Node $node): bool => $this->nodeComparator->areNodesEqual($node, $variable)
        );

        if (! $isFoundPrev) {
            return null;
        }

        // there is "$string[] = ...;", which would cause error in PHP 7+
        // fallback - if no array init found, retype to (array)
        $assign = new Assign($variable, new ArrayCast($variable));
        $this->addNodeAfterNode(clone $node, $node);

        return $assign;
    }

    /**
     * @param PropertyFetch|StaticPropertyFetch $propertyFetchExpr
     */
    private function refactorPropertyFetch(Expr $propertyFetchExpr): bool
    {
        foreach ($this->emptyStringProperties as $emptyStringProperty) {
            if (! $this->nodeNameResolver->areNamesEqual($emptyStringProperty, $propertyFetchExpr)) {
                continue;
            }

            $emptyStringProperty->default = new Array_();
            return true;
        }

        return false;
    }

    /**
     * @param Variable|PropertyFetch|StaticPropertyFetch|Expr $expr
     */
    private function processVariable(Assign $assign, Expr $expr): bool
    {
        if ($this->shouldSkipVariable($expr)) {
            return true;
        }

        $variableAssign = $this->betterNodeFinder->findFirstPrevious($assign, function (Node $node) use ($expr): bool {
            if (! $node instanceof Assign) {
                return false;
            }

            if (! $this->nodeComparator->areNodesEqual($node->var, $expr)) {
                return false;
            }

            // we look for variable assign = string
            if (! $node->expr instanceof String_) {
                return false;
            }

            return $this->valueResolver->isValue($node->expr, '');
        });

        if ($variableAssign instanceof Assign) {
            $variableAssign->expr = new Array_();
            return true;
        }

        return false;
    }

    private function shouldSkipVariable(Expr $expr): bool
    {
        $staticType = $this->getStaticType($expr);
        if ($staticType instanceof ErrorType) {
            return false;
        }

        if ($staticType instanceof UnionType) {
            return ! ($staticType->isSuperTypeOf(new ArrayType(new MixedType(), new MixedType()))->yes() &&
                $staticType->isSuperTypeOf(new ConstantStringType(''))
                    ->yes());
        }

        return ! $staticType instanceof StringType;
    }
}
