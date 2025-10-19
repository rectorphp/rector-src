<?php

declare(strict_types=1);

namespace Rector\Php73\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\ContainsStmts;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Reflection\ReflectionProvider;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\ValueObject\PolyfillPackage;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Rector\VersionBonding\Contract\RelatedPolyfillInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * This needs to removed 1 floor above, because only nodes in arrays can be removed why traversing,
 * see https://github.com/nikic/PHP-Parser/issues/389
 *
 * @see \Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\ArrayKeyFirstLastRectorTest
 */
final class ArrayKeyFirstLastRector extends AbstractRector implements MinPhpVersionInterface, RelatedPolyfillInterface
{
    /**
     * @var string
     */
    private const ARRAY_KEY_FIRST = 'array_key_first';

    /**
     * @var string
     */
    private const ARRAY_KEY_LAST = 'array_key_last';

    /**
     * @var array<string, string>
     */
    private const PREVIOUS_TO_NEW_FUNCTIONS = [
        'reset' => self::ARRAY_KEY_FIRST,
        'end' => self::ARRAY_KEY_LAST,
    ];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Make use of array_key_first() and array_key_last()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
reset($items);
$firstKey = key($items);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$firstKey = array_key_first($items);
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
end($items);
$lastKey = key($items);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$lastKey = array_key_last($items);
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
        return [ContainsStmts::class];
    }

    /**
     * @param ContainsStmts $node
     */
    public function refactor(Node $node): ?ContainsStmts
    {
        return $this->processArrayKeyFirstLast($node);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ARRAY_KEY_FIRST_LAST;
    }

    public function providePolyfillPackage(): string
    {
        return PolyfillPackage::PHP_73;
    }

    private function processArrayKeyFirstLast(ContainsStmts $containsStmts, int $jumpToKey = 0): ?ContainsStmts
    {
        if ($containsStmts->getStmts() === []) {
            return null;
        }

        /** @var int $totalKeys */
        $totalKeys = array_key_last($containsStmts->getStmts());
        for ($key = $jumpToKey; $key < $totalKeys; ++$key) {
            if (! isset($containsStmts->getStmts()[$key], $containsStmts->getStmts()[$key + 1])) {
                break;
            }

            if (! $containsStmts->getStmts()[$key] instanceof Expression) {
                continue;
            }

            /** @var Expression $stmt */
            $stmt = $containsStmts->getStmts()[$key];
            if ($this->shouldSkip($stmt)) {
                continue;
            }

            $nextStmt = $containsStmts->getStmts()[$key + 1];

            /** @var FuncCall $resetOrEndFuncCall */
            $resetOrEndFuncCall = $stmt->expr;

            $keyFuncCall = $this->resolveKeyFuncCall($nextStmt, $resetOrEndFuncCall);
            if (! $keyFuncCall instanceof FuncCall) {
                continue;
            }

            if ($this->hasInternalPointerChangeNext($containsStmts, $key + 1, $totalKeys, $keyFuncCall)) {
                continue;
            }

            if (! isset(self::PREVIOUS_TO_NEW_FUNCTIONS[$this->getName($stmt->expr)])) {
                continue;
            }

            $newName = self::PREVIOUS_TO_NEW_FUNCTIONS[$this->getName($stmt->expr)];
            $keyFuncCall->name = new Name($newName);

            $this->changeNextKeyCall($containsStmts, $key + 2, $resetOrEndFuncCall, $keyFuncCall->name);

            unset($containsStmts->getStmts()[$key]);

            return $containsStmts;
        }

        return null;
    }

    private function changeNextKeyCall(
        ContainsStmts $containsStmts,
        int $key,
        FuncCall $resetOrEndFuncCall,
        Name $newName
    ): void {
        $counter = count($containsStmts->getStmts());
        for ($nextKey = $key; $nextKey < $counter; ++$nextKey) {
            if (! isset($containsStmts->getStmts()[$nextKey])) {
                break;
            }

            if ($containsStmts->getStmts()[$nextKey] instanceof Expression && ! $this->shouldSkip(
                $containsStmts->getStmts()[$nextKey]
            )) {
                $this->processArrayKeyFirstLast($containsStmts, $nextKey);
                break;
            }

            $keyFuncCall = $this->resolveKeyFuncCall($containsStmts->getStmts()[$nextKey], $resetOrEndFuncCall);
            if (! $keyFuncCall instanceof FuncCall) {
                continue;
            }

            $keyFuncCall->name = $newName;
        }
    }

    private function resolveKeyFuncCall(Stmt $nextStmt, FuncCall $resetOrEndFuncCall): ?FuncCall
    {
        if ($resetOrEndFuncCall->isFirstClassCallable()) {
            return null;
        }

        /** @var FuncCall|null */
        return $this->betterNodeFinder->findFirst($nextStmt, function (Node $subNode) use (
            $resetOrEndFuncCall
        ): bool {
            if (! $subNode instanceof FuncCall) {
                return false;
            }

            if (! $this->isName($subNode, 'key')) {
                return false;
            }

            if ($subNode->isFirstClassCallable()) {
                return false;
            }

            return $this->nodeComparator->areNodesEqual($resetOrEndFuncCall->getArgs()[0], $subNode->getArgs()[0]);
        });
    }

    private function hasInternalPointerChangeNext(
        ContainsStmts $containsStmts,
        int $nextKey,
        int $totalKeys,
        FuncCall $funcCall
    ): bool {
        for ($key = $nextKey; $key <= $totalKeys; ++$key) {
            if (! isset($containsStmts->getStmts()[$key])) {
                continue;
            }

            $hasPrevCallNext = (bool) $this->betterNodeFinder->findFirst(
                $containsStmts->getStmts()[$key],
                function (Node $subNode) use ($funcCall): bool {
                    if (! $subNode instanceof FuncCall) {
                        return false;
                    }

                    if (! $this->isNames($subNode, ['prev', 'next'])) {
                        return false;
                    }

                    if ($subNode->isFirstClassCallable()) {
                        return true;
                    }

                    return $this->nodeComparator->areNodesEqual(
                        $subNode->getArgs()[0]
                            ->value,
                        $funcCall->getArgs()[0]
                            ->value
                    );
                }
            );

            if ($hasPrevCallNext) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkip(Expression $expression): bool
    {
        if (! $expression->expr instanceof FuncCall) {
            return true;
        }

        if (! $this->isNames($expression->expr, ['reset', 'end'])) {
            return true;
        }

        if (! $this->reflectionProvider->hasFunction(new Name(self::ARRAY_KEY_FIRST), null)) {
            return true;
        }

        return ! $this->reflectionProvider->hasFunction(new Name(self::ARRAY_KEY_LAST), null);
    }
}
