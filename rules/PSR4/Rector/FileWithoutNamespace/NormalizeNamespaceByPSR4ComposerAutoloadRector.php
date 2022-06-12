<?php

declare(strict_types=1);

namespace Rector\PSR4\Rector\FileWithoutNamespace;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\Contract\Rector\AllowEmptyConfigurableRectorInterface;
use Rector\Core\NodeAnalyzer\InlineHTMLAnalyzer;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;
use Rector\PSR4\NodeManipulator\FullyQualifyStmtsAnalyzer;
use Rector\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\NormalizeNamespaceByPSR4ComposerAutoloadRectorTest
 */
final class NormalizeNamespaceByPSR4ComposerAutoloadRector extends AbstractRector implements AllowEmptyConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const MIGRATE_INNER_CLASS_REFERENCE = 'remove_assign_side_effect';

    /**
     * Default to false, which keep inner class to point FQCN, eg:
     *
     *      Bar to \Bar
     *
     * Set to true will change
     *
     *      Bar to \NewNamespace\Bar
     *
     * with NewNamespace defined in the composer.json
     */
    private bool $migrateInnerClassReference = false;

    public function __construct(
        private readonly PSR4AutoloadNamespaceMatcherInterface $psr4AutoloadNamespaceMatcher,
        private readonly FullyQualifyStmtsAnalyzer $fullyQualifyStmtsAnalyzer,
        private readonly InlineHTMLAnalyzer $inlineHTMLAnalyzer
    ) {
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->migrateInnerClassReference = $configuration[self::MIGRATE_INNER_CLASS_REFERENCE] ?? (bool) current(
            $configuration
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $description = sprintf(<<<'TEXT'
            Adds namespace to namespace-less files or correct namespace to match PSR-4 in `composer.json` autoload section. For example, you have the following `composer.json`:

            ```json
            {
                "autoload": {
                    "psr-4": {
                        "App\\CustomNamespace\\": "src"
                    }
                }
            }
            ```

            Run with combination with "%s".
            TEXT,
            MultipleClassFileToPsr4ClassesRector::class
        );

        return new RuleDefinition($description, [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
// src/SomeClass.php

class SomeClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// src/SomeClass.php

namespace App\CustomNamespace;

class SomeClass
{
}
CODE_SAMPLE
                ,
                [
                    self::MIGRATE_INNER_CLASS_REFERENCE => false
                ]
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
        $processNode = clone $node;
        if ($this->inlineHTMLAnalyzer->hasInlineHTML($processNode)) {
            return null;
        }

        $expectedNamespace = $this->psr4AutoloadNamespaceMatcher->getExpectedNamespace($this->file, $processNode);
        if ($expectedNamespace === null) {
            return null;
        }

        // is namespace and already correctly named?
        if ($processNode instanceof Namespace_ && $this->nodeNameResolver->isCaseSensitiveName(
            $processNode,
            $expectedNamespace
        )) {
            return null;
        }

        if ($processNode instanceof Namespace_ && $this->hasNamespaceInPreviousNamespace($processNode)) {
            return null;
        }

        // to put declare_strict types on correct place
        if ($processNode instanceof FileWithoutNamespace) {
            return $this->refactorFileWithoutNamespace($processNode, $expectedNamespace);
        }

        $processNode->name = new Name($expectedNamespace);
        $this->fullyQualifyStmtsAnalyzer->process(
            $processNode->stmts,
            $expectedNamespace,
            $this->migrateInnerClassReference
        );

        return $processNode;
    }

    private function hasNamespaceInPreviousNamespace(Namespace_ $namespace): bool
    {
        return (bool) $this->betterNodeFinder->findFirstPrevious(
            $namespace,
            fn (Node $node): bool => $node instanceof Namespace_
        );
    }

    private function refactorFileWithoutNamespace(
        FileWithoutNamespace $fileWithoutNamespace,
        string $expectedNamespace
    ): Namespace_ {
        $nodes = $fileWithoutNamespace->stmts;

        $nodesWithStrictTypesThenNamespace = [];
        foreach ($nodes as $key => $fileWithoutNamespace) {
            if ($fileWithoutNamespace instanceof Declare_) {
                $nodesWithStrictTypesThenNamespace[] = $fileWithoutNamespace;
                unset($nodes[$key]);
            }
        }

        $namespace = new Namespace_(new Name($expectedNamespace), $nodes);
        $nodesWithStrictTypesThenNamespace[] = $namespace;

        $this->fullyQualifyStmtsAnalyzer->process($nodes, $expectedNamespace, $this->migrateInnerClassReference);

        return $namespace;
    }
}
