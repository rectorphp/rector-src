<?php

declare(strict_types=1);

namespace Rector\Contract\Rector;

use Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface;
use PhpParser\Node;

/**
 * @template TNode of Node = Node
 * @extends RectorInterface<TNode>
 */
interface ConfigurableRectorInterface extends RectorInterface, ConfigurableRuleInterface
{
    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void;
}
