<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Transform\ValueObject\MethodCallToAnotherMethodCallWithArguments;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\MethodCall\MethodCallToAnotherMethodCallWithArgumentsRector\MethodCallToAnotherMethodCallWithArgumentsRectorTest
 */
final class MethodCallToAnotherMethodCallWithArgumentsRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const METHOD_CALL_RENAMES_WITH_ADDED_ARGUMENTS = 'method_call_renames_with_added_arguments';

    /**
     * @var MethodCallToAnotherMethodCallWithArguments[]
     */
    private array $methodCallRenamesWithAddedArguments = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turns old method call with specific types to new one with arguments', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$serviceDefinition = new Nette\DI\ServiceDefinition;
$serviceDefinition->setInject();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$serviceDefinition = new Nette\DI\ServiceDefinition;
$serviceDefinition->addTag('inject');
CODE_SAMPLE
                ,
                [
                    self::METHOD_CALL_RENAMES_WITH_ADDED_ARGUMENTS => [
                        new MethodCallToAnotherMethodCallWithArguments(
                            'Nette\DI\ServiceDefinition',
                            'setInject',
                            'addTag',
                            ['inject']
                        ),
                    ],
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->methodCallRenamesWithAddedArguments as $methodCallRenameWithAddedArgument) {
            if (! $this->isObjectType($node->var, $methodCallRenameWithAddedArgument->getObjectType())) {
                continue;
            }

            if (! $this->isName($node->name, $methodCallRenameWithAddedArgument->getOldMethod())) {
                continue;
            }

            $node->name = new Identifier($methodCallRenameWithAddedArgument->getNewMethod());
            $node->args = $this->nodeFactory->createArgs($methodCallRenameWithAddedArgument->getNewArguments());

            return $node;
        }

        return null;
    }

    /**
     * @param array<string, MethodCallToAnotherMethodCallWithArguments[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $methodCallRenamesWithAddedArguments = $configuration[self::METHOD_CALL_RENAMES_WITH_ADDED_ARGUMENTS] ?? [];
        Assert::allIsInstanceOf(
            $methodCallRenamesWithAddedArguments,
            MethodCallToAnotherMethodCallWithArguments::class
        );
        $this->methodCallRenamesWithAddedArguments = $methodCallRenamesWithAddedArguments;
    }
}
