<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Removing\ValueObject\RemoveParentMethodCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class RemoveParentMethodCallRector extends \Rector\Core\Rector\AbstractRector implements ConfigurableRectorInterface
{
    /** @var RemoveParentMethodCall[] */
    private array $removedParentCalles;

    public function __construct(private readonly ReflectionResolver $reflectionResolver)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Removes call to parent method',
            [
                new ConfiguredCodeSample(<<<CODE
class SomeClass
{
    public function __construct(\$foo) {
       parent::__construct();
       \$this->foo = \$foo
    }
}
CODE,
                    <<<CODE
class SomeClass
{
    public function __construct(\$foo) {
       \$this->foo = \$foo
    }
}
CODE,
                    [new RemoveParentMethodCall('SomeClass', '__construct')]
                )
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [
            StaticCall::class
        ];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->class, 'parent')) {
            return null;
        }

        $class = $this->reflectionResolver->resolveClassReflection($node);
        if ($class === null) {
            return null;
        }

        $methods = $this->findMethodsByParentClasses($class->getParentClassesNames());

        if ($this->isNames($node->name, $methods)) {
            $this->removeNode($node);
        }

        return null;
    }

    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RemoveParentMethodCall::class);
        $this->removedParentCalles = $configuration;
    }

    /**
     * @param string[] $parentClasses
     * @return string[]
     */
    private function findMethodsByParentClasses(array $parentClasses): array
    {
        return array_map(
            static fn(RemoveParentMethodCall $methodCall) => strtolower($methodCall->getMethodName()),
            $this->filterConfiguredParents($parentClasses)
        );
    }

    /**
     * @param string[] $parentClasses
     * @return RemoveParentMethodCall[]
     */
    private function filterConfiguredParents(array $parentClasses): array
    {
        return array_filter(
            $this->removedParentCalles,
            static fn(RemoveParentMethodCall $methodCall) => in_array($methodCall->getParentClass(), $parentClasses, true)
        );
    }
}

