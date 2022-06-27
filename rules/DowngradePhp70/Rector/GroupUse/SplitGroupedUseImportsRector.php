<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\GroupUse;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\UseImportsResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/group_use_declarations
 *
 * @see \Rector\Tests\DowngradePhp70\Rector\GroupUse\SplitGroupedUseImportsRector\SplitGroupedUseImportsRectorTest
 */
final class SplitGroupedUseImportsRector extends AbstractRector
{
    public function __construct(private readonly UseImportsResolver $useImportsResolver)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor grouped use imports to standalone lines', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use SomeNamespace\{
    First,
    Second
};
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
use SomeNamespace\First;
use SomeNamespace\Second;
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [GroupUse::class];
    }

    /**
     * @param GroupUse $node
     * @return Use_[]
     */
    public function refactor(Node $node): array
    {
        $uses = $this->useImportsResolver->resolveBareUsesForNode($node);

        /**
         * when combined with Use_, it got duplicated ;
         * so ; need to be removed early
         */
        if ($uses !== []) {
            $oldTokens = $this->file->getOldTokens();
            $endTokenPost = $node->getEndTokenPos();
            unset($oldTokens[$endTokenPost + 1]);
            $oldTokens = array_values($oldTokens);
            $this->file->setOldTokens($oldTokens);
        }

        $prefix = $this->getName($node->prefix);

        $uses = [];
        foreach ($node->uses as $useUse) {
            $useUse->name = new Name($prefix . '\\' . $this->getName($useUse->name));
            $uses[] = new Use_([$useUse], $node->type);
        }

        return $uses;
    }
}
