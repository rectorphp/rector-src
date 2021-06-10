<?php

declare(strict_types=1);

namespace Rector\Arguments\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Arguments\ArgumentDefaultValueReplacer;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\ReplaceArgumentDefaultValueRectorTest
 */
final class ReplaceArgumentDefaultValueRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const REPLACED_ARGUMENTS = 'replaced_arguments';

    /**
     * @var ReplaceArgumentDefaultValue[]
     */
    private array $replacedArguments = [];

    public function __construct(
        private ArgumentDefaultValueReplacer $argumentDefaultValueReplacer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces defined map of arguments in defined methods and their calls.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$someObject = new SomeClass;
$someObject->someMethod(SomeClass::OLD_CONSTANT);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$someObject = new SomeClass;
$someObject->someMethod(false);'
CODE_SAMPLE
                    ,
                    [
                        self::REPLACED_ARGUMENTS => [
                            new ReplaceArgumentDefaultValue(
                                'SomeExampleClass',
                                'someMethod',
                                0,
                                'SomeClass::OLD_CONSTANT',
                                false
                            ),
                        ],
                    ]
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class, ClassMethod::class];
    }

    /**
     * @param MethodCall|StaticCall|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->replacedArguments as $replacedArgument) {
            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                $replacedArgument->getObjectType()
            )) {
                continue;
            }

            if (! $this->isName($node->name, $replacedArgument->getMethod())) {
                continue;
            }

            $this->argumentDefaultValueReplacer->processReplaces($node, $replacedArgument);
        }

        return $node;
    }

    /**
     * @param array<string, ReplaceArgumentDefaultValue[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $replacedArguments = $configuration[self::REPLACED_ARGUMENTS] ?? [];
        Assert::allIsInstanceOf($replacedArguments, ReplaceArgumentDefaultValue::class);
        $this->replacedArguments = $replacedArguments;
    }
}
