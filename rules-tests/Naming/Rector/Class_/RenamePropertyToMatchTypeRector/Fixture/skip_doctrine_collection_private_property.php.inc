<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class SkipDoctrineCollectionPrivateProperty
{
    /**
     * @var Collection<int, Checkbox>
     */
    private Collection $checkboxes;

    public function __construct()
    {
        $this->checkboxes = new ArrayCollection();
    }

    public function getCheckboxes(): Collection
    {
        return $this->checkboxes;
    }
}
