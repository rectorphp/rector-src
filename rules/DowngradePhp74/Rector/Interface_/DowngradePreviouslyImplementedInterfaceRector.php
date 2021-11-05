<?php

declare(strict_types=1);

namespace Rector\DowngradePhp74\Rector\Identical;

use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp74\Rector\Interface_\DowngradePreviouslyImplementedInterfaceRector\DowngradePreviouslyImplementedInterfaceRectorTest
 */
final class DowngradePreviouslyImplementedInterfaceRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private const FUNC_FREAD_FWRITE = ['fread', 'fwrite'];

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
        return $node;
    }
}
