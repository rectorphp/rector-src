<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

final class SkipDoctrineSymfonyInCode
{
    public function run(Assert\Choice $choice): ORM\Column
    {
        $constraints = [new Assert\Length(max: 255), Assert\Choice::class];

        return new ORM\Column();
    }
}
