<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\StringClassNameConstantDefaultValue\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ChangeStringClassNameToOtherStringClassName2Rector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('change string class name to other string class name', []);
    }

    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    /**
     * @param String_ $node
     */
    public function refactor(Node $node): ?String_
    {
        if ($node->value === 'Rector\Config\RectorConfig') {
            $node->value = 'Rector\Set\ValueObject\SetList';
            $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);

            return $node;
        }

        return null;
    }
}
