<?php

declare(strict_types=1);

namespace Rector\DowngradePhp74\Rector\Interface_;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Interface_;
use Rector\Core\Rector\AbstractRector;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp74\Rector\Interface_\DowngradePreviouslyImplementedInterfaceRector\DowngradePreviouslyImplementedInterfaceRectorTest
 */
final class DowngradePreviouslyImplementedInterfaceRector extends AbstractRector
{
    public function __construct(
        private FamilyRelationsAnalyzer $familyRelationsAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Downgrade previously implemented interface',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
interface ContainerExceptionInterface extends Throwable
{
}

interface ExceptionInterface extends ContainerExceptionInterface, Throwable
{
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
interface ContainerExceptionInterface extends Throwable
{
}

interface ExceptionInterface extends ContainerExceptionInterface
{
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Interface_::class];
    }

    /**
     * @param Interface_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $extends = $node->extends;

        if (count($extends) < 2) {
            return null;
        }

        $collectInterfaces = [];
        foreach ($extends as $key => $extend) {
            if (! $extend instanceof FullyQualified) {
                continue;
            }

            if (in_array($extend->toString(), $collectInterfaces, true)) {
                unset($extends[$key]);
                continue;
            }

            $collectInterfaces = array_merge(
                $collectInterfaces,
                $this->familyRelationsAnalyzer->getClassLikeAncestorNames($extend)
            );
        }

        $node->extends = $extends;
        return $node;
    }
}
