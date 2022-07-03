<?php

declare(strict_types=1);

namespace Rector\Php54\Rector\Array_;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php54\Rector\Array_\ArrayToShortArrayRector\ArrayToShortArrayRectorTest
 */
final class ArrayToShortArrayRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(private readonly CurrentFileProvider $currentFileProvider)
    {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SHORT_ARRAY;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Array to short array',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return array();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return [];
    }
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
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $file = $this->currentFileProvider->getFile();
        if (! $file instanceof File) {
            return null;
        }

        $oldTokens = $file->getOldTokens();
        $startTokenPos = $node->getStartTokenPos();
        if (! isset($oldTokens[$startTokenPos][1])) {
            return null;
        }

        $arrayStart = $oldTokens[$startTokenPos][1];
        if ($arrayStart === '[') {
            return null;
        }

        return new Array_($node->items);
    }
}
