<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp81;

final class IntersectionIterableNoReturnType
{
    /** @var \Doctrine\Common\Collections\Collection<\stdClass> */
    private \Doctrine\Common\Collections\Collection $socialAccounts;

    public function __construct()
    {
        $this->socialAccounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /** @return \Doctrine\Common\Collections\Collection<\stdClass> */
    public function getSocialAccounts()
    {
        return $this->socialAccounts;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp81;

final class IntersectionIterableNoReturnType
{
    /** @var \Doctrine\Common\Collections\Collection<\stdClass> */
    private \Doctrine\Common\Collections\Collection $socialAccounts;

    public function __construct()
    {
        $this->socialAccounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /** @return \Doctrine\Common\Collections\Collection<\stdClass> */
    public function getSocialAccounts(): \Doctrine\Common\Collections\Collection
    {
        return $this->socialAccounts;
    }
}

?>
