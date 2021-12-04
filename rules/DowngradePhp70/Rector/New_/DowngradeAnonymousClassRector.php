<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\New_;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\NodeDecorator\NamespacedNameDecorator;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp70\NodeFactory\ClassFromAnonymousFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\DowngradeAnonymousClassRectorTest
 */
final class DowngradeAnonymousClassRector extends AbstractRector
{
    /**
     * @var string
     */
    private const ANONYMOUS_CLASS_PREFIX = 'Anonymous__';

    /**
     * @var Class_[]
     */
    private array $classes = [];

    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly ClassFromAnonymousFactory $classFromAnonymousFactory,
        private readonly NamespacedNameDecorator $namespacedNameDecorator
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove anonymous class',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return new class {
            public function execute()
            {
            }
        };
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class Anonymous
{
    public function execute()
    {
    }
}
class SomeClass
{
    public function run()
    {
        return new Anonymous();
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param Node[] $nodes
     * @return Node[]|null
     */
    public function beforeTraverse(array $nodes): ?array
    {
        $this->classes = [];
        return parent::beforeTraverse($nodes);
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function afterTraverse(array $nodes)
    {
        if ($this->classes === []) {
            return $nodes;
        }

        return array_merge($nodes, $this->classes);
    }

    /**
     * @param New_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->classAnalyzer->isAnonymousClass($node->class)) {
            return null;
        }

        if (! $node->class instanceof Class_) {
            return null;
        }

        $className = $this->createAnonymousClassName();

        $class = $this->classFromAnonymousFactory->create($className, $node->class);
        $this->classes[] = $class;

        $this->namespacedNameDecorator->decorate($class);

        return new New_(new Name($className), $node->args);
    }

    private function createAnonymousClassName(): string
    {
        $smartFileInfo = $this->file->getSmartFileInfo();

        return self::ANONYMOUS_CLASS_PREFIX . md5($smartFileInfo->getRealPath()) . '__' . count($this->classes);
    }
}
