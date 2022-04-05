<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\Namespace_;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Removing\ValueObject\RemoveNamespace;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Removing\Rector\Namespace_\RemoveNamespaceRector\RemoveNamespaceRectorTest
 */
class RemoveNamespaceRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var RemoveNamespace[]
     */
    private array $removeNamespaces = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove namespace by configured namespace name', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
namespace App;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
CODE_SAMPLE
                ,
                [new RemoveNamespace('App')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Namespace_::class];
    }

    /**
     * @param Namespace_ $node
     * @return Stmt[]|Node|null
     */
    public function refactor(Node $node): array|Node|null
    {
        $namespaceName = $this->nodeNameResolver->getName($node);

        if ($namespaceName === null) {
            return null;
        }

        foreach ($this->removeNamespaces as $removeNamespace) {
            $removeNamespaceName = $removeNamespace->getNamespace();
            if ($removeNamespaceName !== $namespaceName) {
                continue;
            }

            return $this->processRemoveNamespace($node);
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RemoveNamespace::class);
        $this->removeNamespaces = $configuration;
    }

    /**
     * @return Stmt[]|Namespace_
     */
    private function processRemoveNamespace(Namespace_ $namespace): array|Namespace_
    {
        $stmts = $this->cleanNonCompoundUseName($namespace->stmts);
        if ($stmts === []) {
            $this->removeNode($namespace);
            return $namespace;
        }

        return $stmts;
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    private function cleanNonCompoundUseName(array $stmts): array
    {
        foreach ($stmts as $key => $stmt) {
            if (! $stmt instanceof Use_) {
                continue;
            }

            $uses = $stmt->uses;
            foreach ($uses as $keyUse => $use) {
                $useName = ltrim($use->name->toString(), '\\');
                if (! str_contains($useName, '\\')) {
                    unset($uses[$keyUse]);
                }
            }

            if ($uses === []) {
                unset($stmts[$key]);
                continue;
            }

            $uses = array_values($uses);
            $stmt->uses = $uses;
        }

        return $stmts;
    }
}
