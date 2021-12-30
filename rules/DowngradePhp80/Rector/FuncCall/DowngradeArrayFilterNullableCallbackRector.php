<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/function.array-filter.php#refsect1-function.array-filter-changelog
 *
 * @see Rector\Tests\DowngradePhp80\Rector\FuncCall\DowngradeArrayFilterNullableCallbackRector\DowngradeArrayFilterNullableCallbackRectorTest
 */
final class DowngradeArrayFilterNullableCallbackRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Unset nullable callback on array_filter',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $data = [[]];
        var_dump(array_filter($data, null));
        var_dump(array_filter($data, null, ARRAY_FILTER_USE_KEY));
        var_dump(array_filter($data, null, ARRAY_FILTER_USE_BOTH));
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $data = [[]];
        var_dump(array_filter($data));
        var_dump(array_filter($data));
        var_dump(array_filter($data));
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?FuncCall
    {
        $args = $node->getArgs();
        if (! $this->shouldSkip($node, $args)) {
            return null;
        }

        return $node;
    }

    /**
     * @param Args[] $args
     * @return FuncCall
     */
    private function shouldSkip(FuncCall $funcCall, array $args): bool
    {
        if (! $this->isName($funcCall, 'array_filter')) {
            return true;
        }

        if (! $args[1]) {
            return true;
        }

        return ! $this->valueResolver->isNull($args[1]->value);
    }
}
