<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\StringClassNameConstantDefaultValue\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ChangeStringClassNameToOtherStringClassNameRector extends AbstractRector
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
        if ($node->value === 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\SomeUser') {
            $node->value = 'Rector\Config\RectorConfig';
            return $node;
        }

        return null;
    }
}
