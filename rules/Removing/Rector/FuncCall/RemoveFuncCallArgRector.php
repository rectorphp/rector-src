<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Removing\ValueObject\RemoveFuncCallArg;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Removing\Rector\FuncCall\RemoveFuncCallArgRector\RemoveFuncCallArgRectorTest
 */
final class RemoveFuncCallArgRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const REMOVED_FUNCTION_ARGUMENTS = 'removed_function_arguments';

    /**
     * @var RemoveFuncCallArg[]
     */
    private array $removedFunctionArguments = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove argument by position by function name', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
remove_last_arg(1, 2);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
remove_last_arg(1);
CODE_SAMPLE
                ,
                [new RemoveFuncCallArg('remove_last_arg', 1)]
            ),
        ]);
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
        if ($node->name instanceof Expr) {
            return null;
        }

        foreach ($this->removedFunctionArguments as $removedFunctionArgument) {
            if (! $this->isName($node->name, $removedFunctionArgument->getFunction())) {
                continue;
            }

            foreach (array_keys($node->args) as $position) {
                if ($removedFunctionArgument->getArgumentPosition() !== $position) {
                    continue;
                }

                $this->nodeRemover->removeArg($node, $position);
            }
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $removedFunctionArguments = $configuration[self::REMOVED_FUNCTION_ARGUMENTS] ?? $configuration;
        Assert::allIsAOf($removedFunctionArguments, RemoveFuncCallArg::class);
        $this->removedFunctionArguments = $removedFunctionArguments;
    }
}
