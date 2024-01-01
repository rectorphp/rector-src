<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\ValueObject\Type;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @api
 */
final class AliasedObjectType extends ObjectType
{
    public function __construct(
        string $alias,
        private readonly string $fullyQualifiedClass
    ) {
        parent::__construct($alias);
    }

    public function getFullyQualifiedName(): string
    {
        return $this->fullyQualifiedClass;
    }

    /**
     * @param Use_::TYPE_* $useType
     */
    public function getUseNode(int $useType): Use_
    {
        $name = new Name($this->fullyQualifiedClass);
        $name->setAttribute(AttributeKey::IS_USEUSE_NAME, true);

        $useUse = new UseUse($name, $this->getClassName());

        $use = new Use_([$useUse]);
        $use->type = $useType;

        return $use;
    }

    public function getShortName(): string
    {
        return $this->getClassName();
    }

    public function areShortNamesEqual(self | FullyQualifiedObjectType $comparedObjectType): bool
    {
        return $this->getShortName() === $comparedObjectType->getShortName();
    }

    public function equals(Type $type): bool
    {
        // compare with FQN classes
        if ($type instanceof TypeWithClassName) {
            if ($type instanceof self && $this->fullyQualifiedClass === $type->getFullyQualifiedName()) {
                return true;
            }

            if ($this->fullyQualifiedClass === $type->getClassName()) {
                return true;
            }
        }

        return parent::equals($type);
    }
}
