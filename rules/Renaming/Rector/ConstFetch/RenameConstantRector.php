<?php

declare(strict_types=1);

namespace Rector\Renaming\Rector\ConstFetch;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Renaming\Rector\ConstFetch\RenameConstantRector\RenameConstantRectorTest
 */
final class RenameConstantRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const OLD_TO_NEW_CONSTANTS = 'old_to_new_constants';

    /**
     * @var array<string, string>
     */
    private array $oldToNewConstants = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace constant by new ones', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        return MYSQL_ASSOC;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        return MYSQLI_ASSOC;
    }
}
CODE_SAMPLE
                ,
                [
                    'MYSQL_ASSOC' => 'MYSQLI_ASSOC',
                    'OLD_CONSTANT' => 'NEW_CONSTANT',
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ConstFetch::class];
    }

    /**
     * @param ConstFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->oldToNewConstants as $oldConstant => $newConstant) {
            if (! $this->isName($node->name, $oldConstant)) {
                continue;
            }

            $node->name = new Name($newConstant);
            return $node;
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $oldToNewConstants = $configuration[self::OLD_TO_NEW_CONSTANTS] ?? $configuration;
        Assert::allString(array_keys($oldToNewConstants));
        Assert::allString($oldToNewConstants);

        /** @var array<string, string> $oldToNewConstants */
        $this->oldToNewConstants = $oldToNewConstants;
    }
}
