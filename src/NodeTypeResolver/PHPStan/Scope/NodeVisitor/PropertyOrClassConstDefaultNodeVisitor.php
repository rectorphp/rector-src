<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeVisitorAbstract;
use Rector\Contract\PhpParser\DecoratingNodeVisitorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class PropertyOrClassConstDefaultNodeVisitor extends NodeVisitorAbstract implements DecoratingNodeVisitorInterface
{
    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Property) {
            foreach ($node->props as $propertyItem) {
                $default = $propertyItem->default;
                if (! $default instanceof Expr) {
                    continue;
                }

                $default->setAttribute(AttributeKey::IS_DEFAULT_PROPERTY_VALUE, true);
            }
        }

        if ($node instanceof ClassConst) {
            foreach ($node->consts as $const) {
                $const->value->setAttribute(AttributeKey::IS_DEFAULT_CLASS_CONST_VALUE, true);
            }
        }

        return null;
    }
}
