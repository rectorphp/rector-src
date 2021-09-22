<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Removing\ValueObject\ArgumentRemover;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Removing\Rector\ClassMethod\ArgumentRemoverRector\ArgumentRemoverRectorTest
 */
final class ArgumentRemoverRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const REMOVED_ARGUMENTS = 'removed_arguments';

    /**
     * @var ArgumentRemover[]
     */
    private array $removedArguments = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Removes defined arguments in defined methods and their calls.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$someObject = new SomeClass;
$someObject->someMethod(true);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$someObject = new SomeClass;
$someObject->someMethod();
CODE_SAMPLE
                    ,
                    [
                        self::REMOVED_ARGUMENTS => [new ArgumentRemover('ExampleClass', 'someMethod', 0, [true])],
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
    public function refactor(Node $node): MethodCall | StaticCall | ClassMethod
    {
        foreach ($this->removedArguments as $removedArgument) {
            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                $removedArgument->getObjectType()
            )) {
                continue;
            }

            if (! $this->isName($node->name, $removedArgument->getMethod())) {
                continue;
            }

            $this->processPosition($node, $removedArgument);
        }

        return $node;
    }

    /**
     * @param array<string, ArgumentRemover[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $removedArguments = $configuration[self::REMOVED_ARGUMENTS] ?? [];
        Assert::allIsInstanceOf($removedArguments, ArgumentRemover::class);
        $this->removedArguments = $removedArguments;
    }

    private function processPosition(
        ClassMethod | StaticCall | MethodCall $node,
        ArgumentRemover $argumentRemover
    ): void {
        if ($argumentRemover->getValue() === null) {
            if ($node instanceof MethodCall || $node instanceof StaticCall) {
                $this->nodeRemover->removeArg($node, $argumentRemover->getPosition());
            } else {
                $this->nodeRemover->removeParam($node, $argumentRemover->getPosition());
            }

            return;
        }

        $match = $argumentRemover->getValue();
        if (isset($match['name'])) {
            $this->removeByName($node, $argumentRemover->getPosition(), $match['name']);
            return;
        }

        // only argument specific value can be removed
        if ($node instanceof ClassMethod) {
            return;
        }

        if (! isset($node->getArgs()[$argumentRemover->getPosition()])) {
            return;
        }

        if ($this->isArgumentValueMatch($node->getArgs()[$argumentRemover->getPosition()], $match)) {
            $this->nodeRemover->removeArg($node, $argumentRemover->getPosition());
        }
    }

    private function removeByName(ClassMethod | StaticCall | MethodCall $node, int $position, string $name): void
    {
        if ($node instanceof MethodCall || $node instanceof StaticCall) {
            if (isset($node->getArgs()[$position]) && $this->isName($node->getArgs()[$position], $name)) {
                $this->nodeRemover->removeArg($node, $position);
            }

            return;
        }

        if (! (isset($node->params[$position]) && $this->isName($node->params[$position], $name))) {
            return;
        }

        $this->nodeRemover->removeParam($node, $position);
    }

    /**
     * @param mixed[] $values
     */
    private function isArgumentValueMatch(Arg $arg, array $values): bool
    {
        $nodeValue = $this->valueResolver->getValue($arg->value);
        return in_array($nodeValue, $values, true);
    }
}
