<?php

declare(strict_types=1);

namespace Rector\PSR4\Rector\FileWithoutNamespace;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\NodeAnalyzer\InlineHTMLAnalyzer;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;
use Rector\PSR4\NodeManipulator\FullyQualifyStmtsAnalyzer;
use Rector\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ComposerJsonAwareCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\NormalizeNamespaceByPSR4ComposerAutoloadRectorTest
 */
final class NormalizeNamespaceByPSR4ComposerAutoloadRector extends AbstractRector
{
    public function __construct(
        private readonly PSR4AutoloadNamespaceMatcherInterface $psr4AutoloadNamespaceMatcher,
        private readonly FullyQualifyStmtsAnalyzer $fullyQualifyStmtsAnalyzer,
        private readonly InlineHTMLAnalyzer $inlineHTMLAnalyzer,
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $description = sprintf(
            'Adds namespace to namespace-less files or correct namespace to match PSR-4 in `composer.json` autoload section. Run with combination with "%s"',
            MultipleClassFileToPsr4ClassesRector::class
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

namespace App\CustomNamespace;

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
     * @return Node|null|Stmt[]
     */
    public function refactor(Node $node): Node|null|array
    {
        if ($this->inlineHTMLAnalyzer->hasInlineHTML($node)) {
            return null;
        }

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

        $renames = $this->getClassLikeRenames($processNode, $expectedNamespace);
        $this->renamedClassesDataCollector->addOldToNewClasses($renames);

        // to put declare_strict types on correct place
        if ($node instanceof FileWithoutNamespace) {
            return $this->refactorFileWithoutNamespace($node, $expectedNamespace);
        }

        $node->name = new Name($expectedNamespace);
        $this->fullyQualifyStmtsAnalyzer->process($node->stmts);

        return $node;
    }

    private function hasNamespaceInPreviousNamespace(Namespace_ $namespace): bool
    {
        return (bool) $this->betterNodeFinder->findFirstPrevious(
            $namespace,
            static fn (Node $node): bool => $node instanceof Namespace_
        );
    }

    /**
     * @return Namespace_|Stmt[]
     */
    private function refactorFileWithoutNamespace(
        FileWithoutNamespace $fileWithoutNamespace,
        string $expectedNamespace
    ): Namespace_|array {
        $nodes = $fileWithoutNamespace->stmts;

        $declare = null;
        $nodesWithStrictTypesThenNamespace = [];

        foreach ($nodes as $key => $fileWithoutNamespace) {
            if ($key > 0) {
                break;
            }

            if ($fileWithoutNamespace instanceof Declare_) {
                $declare = $fileWithoutNamespace;
                unset($nodes[$key]);
            }
        }

        $namespace = new Namespace_(new Name($expectedNamespace), $nodes);
        $nodesWithStrictTypesThenNamespace[] = $namespace;

        $this->fullyQualifyStmtsAnalyzer->process($nodes);

        if ($declare instanceof Declare_) {
            return [$declare, $namespace];
        }

        return $namespace;
    }

    /**
     * @param FileWithoutNamespace|Namespace_ $node
     * @return array<string,string>
     */
    private function getClassLikeRenames(Node $node, string $expectedNamespace): array
    {
        $originalNamespace = $this->nodeNameResolver->getName($node) ?? '';
        $classLikes = $this->betterNodeFinder->findNonAnonymousClassLikes($node);
        $classLikesNames = array_map(
            static function (ClassLike $class): string {
                Assert::notNull($class->name);
                return $class->name->toString();
            },
            $classLikes
        );
        $oldClassLikeNames = array_map(
            static fn (string $name): string => $originalNamespace . '\\' . $name,
            $classLikesNames
        );
        $newClassLikeNames = array_map(
            static fn (string $name): string => $expectedNamespace . '\\' . $name,
            $classLikesNames
        );
        $renames = array_combine($oldClassLikeNames, $newClassLikeNames);
        return $renames;
    }
}
