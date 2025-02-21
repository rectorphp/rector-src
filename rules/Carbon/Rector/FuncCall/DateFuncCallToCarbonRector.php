<?php

declare(strict_types=1);

namespace Rector\Carbon\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Minus;
use PhpParser\Node\Expr\BinaryOp\Mul;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\DateFuncCallToCarbonRectorTest
 */
final class DateFuncCallToCarbonRector extends AbstractRector
{
    private const TIME_UNITS = [
        ['weeks', 604800],
        ['days', 86400],
        ['hours', 3600],
        ['minutes', 60],
        ['seconds', 1],
    ];

    /**
     * @throws PoorDocumentationException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert date() function call to Carbon::now()->format(*)', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $date = date('Y-m-d');
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $date = \Carbon\Carbon::now()->format('Y-m-d');
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
        return [Minus::class, FuncCall::class];
    }

    /**
     * @param FuncCall $node
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Minus) {
            $left = $node->left;
            if ($left instanceof FuncCall && $this->isName($left->name, 'time')) {
                $timeUnit = $this->detectTimeUnit($node->right);
                if ($timeUnit !== null) {
                    return $this->createCarbonSubtract($timeUnit);
                }
            }

            return null;
        }

        if (! $node instanceof FuncCall) {
            return null;
        }

        if ($this->isName($node->name, 'date') && isset($node->args[1]) && $node->args[1] instanceof Arg) {
            $format = $this->getArgValue($node, 0);
            if (! $format instanceof Expr) {
                return null;
            }

            $timestamp = $node->args[1]->value;
            if ($timestamp instanceof FuncCall
                && $this->isName($timestamp->name, 'strtotime')
                && isset($timestamp->args[0]) && $timestamp->args[0] instanceof Arg
            ) {
                $dateExpr = $timestamp->args[0]->value;
                return $this->createCarbonParseFormat($dateExpr, $format);
            }
        }

        if ($this->isName($node->name, 'date') && isset($node->args[0])) {
            $format = $this->getArgValue($node, 0);
            if ($format instanceof String_) {
                return $this->createCarbonNowFormat($format);
            }
        }

        if ($this->isName($node->name, 'strtotime') && isset($node->args[0])) {
            $dateExpr = $this->getArgValue($node, 0);
            if ($dateExpr !== null) {
                return $this->createCarbonParseTimestamp($dateExpr);
            }
        }

        return null;
    }

    /**
     * @param FuncCall $node
     * @param int $index
     * @return Expr|null
     */
    private function getArgValue(FuncCall $node, int $index): ?Expr
    {
        if (!isset($node->args[$index]) || !$node->args[$index] instanceof Arg) {
            return null;
        }

        return $node->args[$index]->value;
    }

    private function createCarbonNowFormat(String_ $format): MethodCall
    {
        $nowCall = new StaticCall(
            new FullyQualified('Carbon\\Carbon'),
            'now'
        );

        return new MethodCall($nowCall, 'format', [new Arg($format)]);
    }

    private function createCarbonParseTimestamp(Expr $dateExpr): PropertyFetch
    {
        $parseCall = new StaticCall(
            new FullyQualified('Carbon\\Carbon'),
            'parse',
            [new Arg($dateExpr)]
        );

        return new PropertyFetch($parseCall, 'timestamp');
    }

    private function createCarbonParseFormat(Expr $dateExpr, Expr $format): MethodCall
    {
        $parseCall = new StaticCall(
            new FullyQualified('Carbon\\Carbon'),
            'parse',
            [new Arg($dateExpr)]
        );

        return new MethodCall($parseCall, 'format', [new Arg($format)]);
    }

    /**
     * @param array{unit: string, value: int} $timeUnit
     * @return PropertyFetch
     */
    private function createCarbonSubtract(array $timeUnit): PropertyFetch
    {
        $nowCall = new StaticCall(
            new FullyQualified('Carbon\\Carbon'),
            'now'
        );
        $methodName = 'sub' . ucfirst($timeUnit['unit']);
        $subtractCall = new MethodCall($nowCall, $methodName, [new Arg(new LNumber($timeUnit['value']))]);
        return new PropertyFetch($subtractCall, 'timestamp');
    }

    /**
     * @param Expr $node
     * @return array{unit: string, value: int}|null
     */
    private function detectTimeUnit(Expr $node): ?array
    {
        $product = $this->calculateProduct($node);
        if ($product === null) {
            return null;
        }

        foreach (self::TIME_UNITS as [$unit, $seconds]) {
            if ($product % $seconds === 0) {
                return [
                    'unit' => (string) $unit,
                    'value' => (int) ($product / $seconds)
                ];
            }
        }

        return null;
    }

    /**
     * @param Expr $node
     * @return int|null
     */
    private function calculateProduct(Expr $node): ?int
    {
        if ($node instanceof LNumber) {
            return $node->value;
        }

        if (!$node instanceof Mul) {
            return null;
        }

        $multipliers = $this->extractMultipliers($node);
        if ($multipliers === []) {
            return null;
        }

        return array_product($multipliers);
    }

    /**
     * @param Node $node
     * @return int[]
     */
    private function extractMultipliers(Node $node): array
    {
        $multipliers = [];
        if (!$node instanceof Mul) {
            return $multipliers;
        }

        if ($node->left instanceof LNumber) {
            $multipliers[] = $node->left->value;
        } elseif ($node->left instanceof Mul) {
            $multipliers = array_merge($multipliers, $this->extractMultipliers($node->left));
        }

        if ($node->right instanceof LNumber) {
            $multipliers[] = $node->right->value;
        } elseif ($node->right instanceof Mul) {
            $multipliers = array_merge($multipliers, $this->extractMultipliers($node->right));
        }

        return $multipliers;
    }
}
