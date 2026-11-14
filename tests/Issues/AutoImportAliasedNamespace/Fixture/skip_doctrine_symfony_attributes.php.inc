<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'product')]
final class SkipDoctrineSymfonyAttributes
{
    #[ORM\Column]
    #[Assert\Length(max: 255)]
    public string $name;

    #[ORM\Column]
    #[Assert\Choice(choices: ['a', 'b'])]
    public string $type;
}
