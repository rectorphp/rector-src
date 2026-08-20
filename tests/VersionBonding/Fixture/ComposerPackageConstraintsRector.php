<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding\Fixture;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\VersionBonding\Contract\ComposerPackageConstraintInterface;
use Rector\VersionBonding\ValueObject\ComposerPackageConstraint;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ComposerPackageConstraintsRector extends AbstractRector implements ComposerPackageConstraintInterface
{
    /**
     * @var ComposerPackageConstraint[]
     */
    private array $composerPackageConstraints;

    public function __construct(ComposerPackageConstraint ...$composerPackageConstraints)
    {
        $this->composerPackageConstraints = $composerPackageConstraints;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Test rector with multiple composer package constraints', []);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }

    /**
     * @return list<ComposerPackageConstraint>
     */
    public function provideComposerPackageConstraint(): array
    {
        return array_values($this->composerPackageConstraints);
    }
}
