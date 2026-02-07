<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding\Fixture;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\VersionBonding\Contract\ComposerPackageConstraintInterface;
use Rector\VersionBonding\ValueObject\ComposerPackageConstraint;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ComposerPackageConstraintRector extends AbstractRector implements ComposerPackageConstraintInterface
{
    public function __construct(
        private readonly string $packageName,
        private readonly string $constraint,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Test rector with composer package constraint', []);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }

    public function provideComposerPackageConstraint(): ComposerPackageConstraint
    {
        return new ComposerPackageConstraint($this->packageName, $this->constraint);
    }
}
