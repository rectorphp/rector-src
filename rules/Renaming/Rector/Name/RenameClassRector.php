<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\Name;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Renaming\NodeManipulator\ClassRenamer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Renaming\Rector\Name\RenameClassRector\RenameClassRectorTest
 */
final class RenameClassRector extends AbstractRector implements ConfigurableRectorInterface
{
    private bool $isMayRequireRestructureNamespace = false;

    public function __construct(
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
        private readonly ClassRenamer $classRenamer,
    ) {
    }

    /**
     * @param Node[] $nodes
     * @return Node[]|null
     */
    public function beforeTraverse(array $nodes): ?array
    {
        $this->isMayRequireRestructureNamespace = false;

        return parent::beforeTraverse($nodes);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces defined classes by new ones.', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
namespace App;

use SomeOldClass;

function someFunction(SomeOldClass $someOldClass): SomeOldClass
{
    if ($someOldClass instanceof SomeOldClass) {
        return new SomeOldClass;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
namespace App;

use SomeNewClass;

function someFunction(SomeNewClass $someOldClass): SomeNewClass
{
    if ($someOldClass instanceof SomeNewClass) {
        return new SomeNewClass;
    }
}
CODE_SAMPLE
                ,
                [
                    'App\SomeOldClass' => 'App\SomeNewClass',
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            Name::class,
            Property::class,
            FunctionLike::class,
            Expression::class,
            ClassLike::class,
            Namespace_::class,
            If_::class,
        ];
    }

    /**
     * @param FunctionLike|Name|ClassLike|Expression|Namespace_|Property|If_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $oldToNewClasses = $this->renamedClassesDataCollector->getOldToNewClasses();
        if ($oldToNewClasses !== []) {
            $scope = $node->getAttribute(AttributeKey::SCOPE);
            $renameNode = $this->classRenamer->renameNode($node, $oldToNewClasses, $scope);

            if ($renameNode instanceof Namespace_) {
                $this->isMayRequireRestructureNamespace = true;
            }

            return $renameNode;
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString($configuration);
        Assert::allString(array_keys($configuration));

        $this->renamedClassesDataCollector->addOldToNewClasses($configuration);
    }

    /**
     * @param Node[] $nodes
     * @return null|Node[]
     */
    public function afterTraverse(array $nodes): ?array
    {
        if (! $this->isMayRequireRestructureNamespace) {
            return parent::afterTraverse($nodes);
        }

        foreach ($nodes as $node) {
            if ($node instanceof Namespace_) {
                return parent::afterTraverse($nodes);
            }

            if (! $node instanceof FileWithoutNamespace) {
                continue;
            }

            foreach ($node->stmts as $stmt) {
                if ($stmt instanceof Namespace_) {
                    $this->restructureUnderNamespace($node);
                    return $node->stmts;
                }
            }
        }

        return parent::afterTraverse($nodes);
    }

    private function restructureUnderNamespace(FileWithoutNamespace $fileWithoutNamespace): void
    {
        $stmts = array_reverse($fileWithoutNamespace->stmts, true);
        $isBeforeNamespace = false;
        $stmtsBeforeNamespace = [];
        $namepace = null;

        foreach ($stmts as $key => $stmt) {
            if ($stmt instanceof Namespace_) {
                $isBeforeNamespace = true;
                $namepace = $stmt;
                continue;
            }

            if ($isBeforeNamespace && ! $stmt instanceof Declare_) {
                $stmtsBeforeNamespace[] = $stmt;
                unset($stmts[$key]);
            }
        }

        if ($stmtsBeforeNamespace === [] || ! $namepace instanceof Namespace_) {
            return;
        }

        $namepace->stmts = array_values([...$stmtsBeforeNamespace, ...$namepace->stmts]);
        $fileWithoutNamespace->stmts = array_values(array_reverse($stmts, true));
    }
}
