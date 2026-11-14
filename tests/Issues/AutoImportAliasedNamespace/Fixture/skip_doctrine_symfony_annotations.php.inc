<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="product")
 */
final class SkipDoctrineSymfonyAnnotations
{
    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max=255)
     */
    public $name;

    /**
     * @var Assert\Choice
     */
    public $choice;
}
