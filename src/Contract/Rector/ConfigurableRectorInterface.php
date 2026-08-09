<?php

declare(strict_types=1);

namespace Rector\Contract\Rector;

use PhpParser\Node;
use Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface;

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
