<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\VariadicPlaceholder;
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
     * @var ArgumentRemover[]
     */
    private array $removedArguments = [];

    private bool $hasChanged = false;

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
                    [new ArgumentRemover('ExampleClass', 'someMethod', 0, [true])]
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
    public function refactor(Node $node): MethodCall | StaticCall | ClassMethod | null
    {
        $this->hasChanged = false;

        foreach ($this->removedArguments as $removedArgument) {
            if (! $this->isName($node->name, $removedArgument->getMethod())) {
                continue;
            }

            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                $removedArgument->getObjectType()
            )) {
                continue;
            }

            $this->processPosition($node, $removedArgument);
        }

        if ($this->hasChanged) {
            return $node;
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, ArgumentRemover::class);
        $this->removedArguments = $configuration;
    }

    private function processPosition(
        ClassMethod | StaticCall | MethodCall $node,
        ArgumentRemover $argumentRemover
    ): void {
        if ($argumentRemover->getValue() === null) {
            if ($node instanceof MethodCall || $node instanceof StaticCall) {
                $this->nodeRemover->removeArg($node, $argumentRemover->getPosition());
            } else {
                unset($node->params[$argumentRemover->getPosition()]);
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

        if (! isset($node->args[$argumentRemover->getPosition()])) {
            return;
        }

        if ($this->isArgumentValueMatch($node->args[$argumentRemover->getPosition()], $match)) {
            $this->hasChanged = true;
            $this->nodeRemover->removeArg($node, $argumentRemover->getPosition());
        }
    }

    private function removeByName(ClassMethod | StaticCall | MethodCall $node, int $position, string $name): void
    {
        if ($node instanceof MethodCall || $node instanceof StaticCall) {
            if (isset($node->args[$position]) && $this->isName($node->args[$position], $name)) {
                $this->nodeRemover->removeArg($node, $position);
            }

            return;
        }

        if (! (isset($node->params[$position]) && $this->isName($node->params[$position], $name))) {
            return;
        }

        unset($node->params[$position]);
    }

    /**
     * @param mixed[] $values
     */
    private function isArgumentValueMatch(Arg|VariadicPlaceholder $arg, array $values): bool
    {
        if (! $arg instanceof Arg) {
            return false;
        }

        $nodeValue = $this->valueResolver->getValue($arg->value);
        return in_array($nodeValue, $values, true);
    }
}
