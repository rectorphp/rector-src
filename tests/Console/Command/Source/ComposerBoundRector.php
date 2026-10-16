<?php

declare(strict_types=1);

namespace Rector\Tests\Console\Command\Source;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\VersionBonding\Contract\ComposerPackageConstraintInterface;
use Rector\VersionBonding\ValueObject\ComposerPackageConstraint;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

final class ComposerBoundRector extends AbstractRector implements ComposerPackageConstraintInterface
{
    public function __construct(
        private readonly string $packageName,
        private readonly string $constraint
    ) {
    }

    public function provideComposerPackageConstraint(): ComposerPackageConstraint
    {
        return new ComposerPackageConstraint($this->packageName, $this->constraint);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Testing rule', [new CodeSample('$before;', '$after;')]);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
