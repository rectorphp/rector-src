<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

/**
 * @template T
 */
final class AvoidInvalidTypeHintSuggestion
{
    /**
     * Same signature as "findOneBy" by Doctrine (ObjectRepository).
     *
     * @psalm-return ?T
     * @return null|object
     */
    public function findOneBy()
    {
        return null;
    }

    /**
     * Shouldn't suggest "?mixed" as type-hint since it's invalid.
     */
    public function myOwnFunctionUsingADoctrineRepository()
    {
        return $this->findOneBy();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

/**
 * @template T
 */
final class AvoidInvalidTypeHintSuggestion
{
    /**
     * Same signature as "findOneBy" by Doctrine (ObjectRepository).
     *
     * @psalm-return ?T
     * @return null|object
     */
    public function findOneBy()
    {
        return null;
    }

    /**
     * Shouldn't suggest "?mixed" as type-hint since it's invalid.
     */
    public function myOwnFunctionUsingADoctrineRepository(): ?object
    {
        return $this->findOneBy();
    }
}

?>
