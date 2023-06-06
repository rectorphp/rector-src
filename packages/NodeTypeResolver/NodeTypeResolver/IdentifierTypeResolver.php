<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\NullType;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;

/**
 * @implements NodeTypeResolverInterface<Identifier>
 */
final class IdentifierTypeResolver implements NodeTypeResolverInterface
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Identifier::class];
    }

    /**
     * @param Identifier $node
     * @return StringType|BooleanType|ConstantBooleanType|NullType|IntegerType|FloatType|MixedType
     */
    public function resolve(Node $node): Type
    {
        $lowerString = $node->toLowerString();

        if ($lowerString === 'string') {
            return new StringType();
        }

        if ($lowerString === 'bool') {
            return new BooleanType();
        }

        if ($lowerString === 'false') {
            return new ConstantBooleanType(false);
        }

        if ($lowerString === 'true') {
            return new ConstantBooleanType(true);
        }

        if ($lowerString === 'null') {
            return new NullType();
        }

        if ($lowerString === 'int') {
            return new IntegerType();
        }

        if ($lowerString === 'float') {
            return new FloatType();
        }

        return new MixedType();
    }
}
