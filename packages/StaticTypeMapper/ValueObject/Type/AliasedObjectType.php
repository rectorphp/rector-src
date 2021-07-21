<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\ValueObject\Type;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class AliasedObjectType extends ObjectType
{
    public function __construct(
        string $alias,
        private string $fullyQualifiedClass
    ) {
        parent::__construct($alias);
    }

    public function getFullyQualifiedClass(): string
    {
        return $this->fullyQualifiedClass;
    }

    public function getUseNode(): Use_
    {
        $name = new Name($this->fullyQualifiedClass);
        $useUse = new UseUse($name, $this->getClassName());
        return new Use_([$useUse]);
    }

    public function getShortName(): string
    {
        return $this->getClassName();
    }

    public function areShortNamesEqual(self | FullyQualifiedObjectType $comparedObjectType): bool
    {
        return $this->getShortName() === $comparedObjectType->getShortName();
    }

    public function getFunctionUseNode(): Use_
    {
        $name = new Name($this->fullyQualifiedClass);
        $useUse = new UseUse($name, $this->getClassName());

        $name->setAttribute(AttributeKey::PARENT_NODE, $useUse);

        $use = new Use_([$useUse]);
        $use->type = Use_::TYPE_FUNCTION;

        return $use;
    }
}
