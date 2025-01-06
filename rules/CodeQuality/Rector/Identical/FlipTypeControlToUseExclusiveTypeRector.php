<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Identical;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Rector\TypeDeclaration\TypeAnalyzer\NullableTypeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\FlipTypeControlToUseExclusiveTypeRectorTest
 */
final class FlipTypeControlToUseExclusiveTypeRector extends AbstractRector
{
    public function __construct(
        private readonly NullableTypeAnalyzer $nullableTypeAnalyzer,
        private readonly ValueResolver $valueResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Flip type control from null compare to use exclusive instanceof object', [
            new CodeSample(
                <<<'CODE_SAMPLE'
function process(?DateTime $dateTime)
{
    if ($dateTime === null) {
        return;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
function process(?DateTime $dateTime)
{
    if (! $dateTime instanceof DateTime) {
        return;
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
        return [Identical::class, NotIdentical::class];
    }

    /**
     * @param Identical|NotIdentical $node
     */
    public function refactor(Node $node): ?Node
    {
        $expr = $this->matchNullComparedExpr($node);
        if (! $expr instanceof Expr) {
            return null;
        }

        $nullableObjectType = $this->nullableTypeAnalyzer->resolveNullableObjectType($expr);
        if (! $nullableObjectType instanceof ObjectType) {
            return null;
        }

        return $this->processConvertToExclusiveType($nullableObjectType, $expr, $node);
    }

    private function processConvertToExclusiveType(
        ObjectType $objectType,
        Expr $expr,
        Identical|NotIdentical $binaryOp
    ): BooleanNot|Instanceof_ {
        $fullyQualifiedType = $objectType instanceof ShortenedObjectType || $objectType instanceof AliasedObjectType
            ? $objectType->getFullyQualifiedName()
            : $objectType->getClassName();

        if ($expr instanceof Assign) {
            $expr->setAttribute(AttributeKey::WRAPPED_IN_PARENTHESES, true);
        }

        $instanceof = new Instanceof_($expr, new FullyQualified($fullyQualifiedType));
        if ($binaryOp instanceof NotIdentical) {
            return $instanceof;
        }

        return new BooleanNot($instanceof);
    }

    private function matchNullComparedExpr(Identical|NotIdentical $binaryOp): ?Expr
    {
        if ($this->valueResolver->isNull($binaryOp->left)) {
            return $binaryOp->right;
        }

        if ($this->valueResolver->isNull($binaryOp->right)) {
            return $binaryOp->left;
        }

        return null;
    }
}
