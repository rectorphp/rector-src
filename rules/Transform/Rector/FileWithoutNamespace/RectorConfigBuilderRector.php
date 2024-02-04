<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\FileWithoutNamespace;

use PhpParser\Node;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector\RectorConfigBuilderTest
 */
final class RectorConfigBuilderRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change RectorConfig to RectorConfigBuilder', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SomeRector::class);
};
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return RectorConfig::configure()->rules([SomeRector::class]);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FileWithoutNamespace::class];
    }

    /**
     * @param FileWithoutNamespace $node
     */
    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
