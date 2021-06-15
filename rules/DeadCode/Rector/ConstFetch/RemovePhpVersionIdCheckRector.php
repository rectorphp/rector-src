<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ConstFetch;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Util\PhpVersionFactory;
use Rector\Core\ValueObject\PhpVersion;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector\RemovePhpVersionIdCheckRectorTest
 */
final class RemovePhpVersionIdCheckRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const PHP_VERSION_CONSTRAINT = 'phpVersionConstraint';

    public int $phpVersionConstraint;

    public function __construct(private PhpVersionFactory $phpVersionFactory)
    {
    }

    /**
     * @param array<string, int|string> $configuration
     */
    public function configure(array $configuration): void
    {
        $phpVersionConstraint = $configuration[self::PHP_VERSION_CONSTRAINT] ?? $this->phpVersionProvider->provide();
        // ensure cast to (string) first to allow string like "8.0" value to be converted to the int value
        $this->phpVersionConstraint = $this->phpVersionFactory->createIntVersion((string) $phpVersionConstraint);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $exampleConfiguration = [
            self::PHP_VERSION_CONSTRAINT => PhpVersion::PHP_80,
        ];
        return new RuleDefinition(
            'Remove unneded PHP_VERSION_ID check',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        if (PHP_VERSION_ID < 80000) {
            return;
        }
        echo 'do something';
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        echo 'do something';
    }
}
CODE_SAMPLE
,
$exampleConfiguration
                ),
            ],
        );
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
        if (! $this->isName($node, 'PHP_VERSION_ID')) {
            return null;
        }

        return $node;
    }
}
