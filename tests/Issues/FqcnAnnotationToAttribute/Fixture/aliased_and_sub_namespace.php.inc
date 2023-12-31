<?php

namespace Rector\Tests\Issues\FqcnAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table("user", indexes={
 *  @ORM\Index(name="name_index", columns={"name"}),
 *  @ORM\Index(name="surname_index", columns={"surname"}),
 * })
 * @Constraints\UniqueEntity("azureB2cUuid")
 * @Constraints\UniqueEntity("uuid")
 * @Constraints\UniqueEntity("email")
 */
class AliasedAndSubNamespace
{
    /**
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Email(mode="strict")
     * @ORM\Column(type="string", unique=true)
     * @Groups({"import", "export-user", "export-claim"})
     */
    protected string $email = "";
}

?>
-----
<?php

namespace Rector\Tests\Issues\FqcnAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[Constraints\UniqueEntity('azureB2cUuid')]
#[Constraints\UniqueEntity('uuid')]
#[Constraints\UniqueEntity('email')]
#[ORM\Table('user')]
#[ORM\Index(name: 'name_index', columns: ['name'])]
#[ORM\Index(name: 'surname_index', columns: ['surname'])]
class AliasedAndSubNamespace
{
    #[SymfonyConstraints\NotBlank]
    #[SymfonyConstraints\Email(mode: 'strict')]
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups(['import', 'export-user', 'export-claim'])]
    protected string $email = "";
}

?>
