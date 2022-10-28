<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp81;

final class SkipIntersectionIterable
{
    /** @var \Doctrine\Common\Collections\Collection<\stdClass> */
    private \Doctrine\Common\Collections\Collection $socialAccounts;

    public function __construct()
    {
        $this->socialAccounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /** @return \Doctrine\Common\Collections\Collection<\stdClass> */
    public function getSocialAccounts() : \Doctrine\Common\Collections\Collection
    {
        return $this->socialAccounts;
    }
}
