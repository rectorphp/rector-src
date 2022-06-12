<?php

declare(strict_types=1);

namespace Rector\PSR4\Rector\FileWithoutNamespace;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Rector\ClassRenamingPostRector;
use Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ComposerJsonAwareCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\PSR4\Rector\FileWithoutNamespace\CollectPSR4ComposerAutoloadNamespaceRenamesRector\CollectPSR4ComposerAutoloadNamespaceRenamesRectorTest
 */
final class CollectPSR4ComposerAutoloadNamespaceRenamesRector extends AbstractRector
{
    public function __construct(
        private readonly PSR4AutoloadNamespaceMatcherInterface $psr4AutoloadNamespaceMatcher,
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $description = sprintf(
            'Collect renames of classes in namespace-less files or files whose namespace do not match PSR-4 in `composer.json` autoload section. Run with combination with “%s” and then “%s” on the second run',
            ClassRenamingPostRector::class,
            NormalizeNamespaceByPSR4ComposerAutoloadRectorTest::class
        );

        return new RuleDefinition($description, [
            new ComposerJsonAwareCodeSample(
                <<<'CODE_SAMPLE'
// src/SomeClass.php

class SomeClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// src/SomeClass.php

// [“SomeClass” would be renamed to “App\CustomNamespace\SomeClass”]
class SomeClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
{
    "autoload": {
        "psr-4": {
            "App\\CustomNamespace\\": "src"
        }
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Namespace_::class, FileWithoutNamespace::class];
    }

    /**
     * @param FileWithoutNamespace|Namespace_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $expectedNamespace = $this->psr4AutoloadNamespaceMatcher->getExpectedNamespace($this->file, $node);
        if ($expectedNamespace === null) {
            return null;
        }

        // is namespace and already correctly named?
        if ($node instanceof Namespace_ && $this->nodeNameResolver->isCaseSensitiveName(
            $node,
            $expectedNamespace
        )) {
            return null;
        }

        if ($node instanceof Namespace_ && $this->hasNamespaceInPreviousNamespace($node)) {
            return null;
        }

        if ($node instanceof Namespace_) {
            $oldNamespace = (string) $node->name;
        } else {
            $oldNamespace = '';
        }

        if ($oldNamespace !== '') {
            $oldNamespace .= '\\';
        }

        $renames = [];

        foreach ($node->stmts as $key => $stmt) {
            if (($stmt instanceof ClassLike && $stmt->name !== null) || $stmt instanceof Function_) {
                $renames[$oldNamespace . $stmt->name] = $expectedNamespace . '\\' . $stmt->name;
            }
        }

        $this->renamedClassesDataCollector->addOldToNewClasses($renames);

        return null;
    }

    private function hasNamespaceInPreviousNamespace(Namespace_ $namespace): bool
    {
        return (bool) $this->betterNodeFinder->findFirstPrevious(
            $namespace,
            fn (Node $node): bool => $node instanceof Namespace_
        );
    }

}
