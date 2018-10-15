<?php declare(strict_types=1);

namespace Rector\Rector\CodeQuality;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\BooleanNot;
use Rector\NodeAnalyzer\ConstFetchAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class SimplifyConditionsRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private $binaryOpClassesToInversedClasses = [
        Identical::class => NotIdentical::class,
        NotIdentical::class => Identical::class,
        Equal::class => NotEqual::class,
        NotEqual::class => Equal::class,
        Greater::class => SmallerOrEqual::class,
        Smaller::class => GreaterOrEqual::class,
        GreaterOrEqual::class => Smaller::class,
        SmallerOrEqual::class => Greater::class,
    ];

    /**
     * @var ConstFetchAnalyzer
     */
    private $constFetchAnalyzer;

    public function __construct(ConstFetchAnalyzer $constFetchAnalyzer)
    {
        $this->constFetchAnalyzer = $constFetchAnalyzer;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Simplify conditions',
            [new CodeSample("if (! (\$foo !== 'bar')) {...", "if (\$foo === 'bar') {...")]
        );
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [BooleanNot::class, Identical::class];
    }

    /**
     * @param BooleanNot|Identical $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof BooleanNot) {
            return $this->processBooleanNot($node);
        }

        if ($node instanceof Identical) {
            return $this->processIdenticalAndNotIdentical($node);
        }
    }

    private function processBooleanNot(BooleanNot $node): Node
    {
        if (! $node->expr instanceof BinaryOp) {
            return $node;
        }

        if ($this->shouldSkip($node->expr)) {
            return $node;
        }

        return $this->createInversedBooleanOp($node->expr);
    }

    private function processIdenticalAndNotIdentical(BinaryOp $node): Node
    {
        if ($node->left instanceof Identical || $node->left instanceof NotIdentical) {
            $subBinaryOpNode = $node->left;
            $shouldInverse = $this->constFetchAnalyzer->isFalse($node->right);
        } elseif ($node->right instanceof Identical || $node->right instanceof NotIdentical) {
            $subBinaryOpNode = $node->right;
            $shouldInverse = $this->constFetchAnalyzer->isFalse($node->left);
        } else {
            return $node;
        }

        if ($shouldInverse) {
            return $this->createInversedBooleanOp($subBinaryOpNode);
        }

        return $subBinaryOpNode;
    }

    private function createInversedBooleanOp(BinaryOp $binaryOpNode): BinaryOp
    {
        $binaryOpNodeClass = get_class($binaryOpNode);

        // we can't invert that
        if (! isset($this->binaryOpClassesToInversedClasses[$binaryOpNodeClass])) {
            return $binaryOpNode;
        }

        $inversedBinaryOpNodeClass = $this->binaryOpClassesToInversedClasses[$binaryOpNodeClass];

        return new $inversedBinaryOpNodeClass($binaryOpNode->left, $binaryOpNode->right);
    }

    /**
     * Skip too nested binary || binary > binary combinations
     */
    private function shouldSkip(BinaryOp $binaryOpNode): bool
    {
        if ($binaryOpNode->left instanceof BinaryOp) {
            return true;
        }

        if ($binaryOpNode->right instanceof BinaryOp) {
            return true;
        }

        return false;
    }
}
