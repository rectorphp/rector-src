<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NullToStrictStringFuncCallArgRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var array<string, array<int, string>>
     */
    private const ARG_POSITION_NAME_NULL_TO_STRICT_STRING = [
        'preg_split' => [
            1 => 'subject',
        ],
        'preg_match' => [
            1 => 'subject',
        ],
        'preg_match_all' => [
            1 => 'subject',
        ],
        'explode' => [
            1
             => 'string',
        ],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change null to strict string defined function call args',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        preg_split("#a#", null);
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        preg_split("#a#", '');
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
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $args = $node->getArgs();
        if ($this->hasNamedArgDefined($node, $args)) {
            return $this->processNamedArgDefined($node);
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_NULL_ARG_IN_STRING_FUNCTION;
    }

    /**
     * @param Arg[] $args
     */
    private function hasNamedArgDefined(FuncCall $funcCall, array $args): bool
    {
        $functionName = $this->nodeNameResolver->getName($funcCall);
        [, $ArgName] = self::ARG_POSITION_NAME_NULL_TO_STRICT_STRING[$functionName];

        foreach ($args as $arg) {
            if (! $arg->name instanceof Identifier) {
                continue;
            }
            if (! $this->nodeNameResolver->isName($arg->name, $ArgName)) {
                continue;
            }
            return true;
        }

        return false;
    }

    private function processNamedArgDefined(FuncCall $funcCall): FuncCall
    {
        return $funcCall;
    }

    private function shouldSkip(FuncCall $funcCall): bool
    {
        $functionNames = array_keys(self::ARG_POSITION_NAME_NULL_TO_STRICT_STRING);
        return ! $this->nodeNameResolver->isNames($funcCall, $functionNames);
    }
}
