<?php

declare(strict_types=1);

namespace Rector\Tests\Config\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RegisteringRector extends AbstractRector
{
    public function __construct(
        private readonly DependencyOnlyRector $dependencyOnlyRector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    public function refactor(Node $node): ?Node
    {
        return $this->dependencyOnlyRector->refactor($node);
    }
}
