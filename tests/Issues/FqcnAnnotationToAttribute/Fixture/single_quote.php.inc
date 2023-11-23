<?php

namespace Rector\Core\Tests\Issues\FqcnAnnotationToAttribute\Fixture;

/**
 * @\Doctrine\ORM\Mapping\Entity()
 * @\Doctrine\ORM\Mapping\Table('user', indexes={
 *  @\Doctrine\ORM\Mapping\Index(name="name_index", columns={"name"}),
 *  @\Doctrine\ORM\Mapping\Index(name="surname_index", columns={"surname"}),
 * })
 * @\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity("azureB2cUuid")
 * @\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity("uuid")
 * @\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity("email")
 */
class SingleQuote
{
    /**
     * @\Symfony\Component\Validator\Constraints\NotBlank()
     * @\Symfony\Component\Validator\Constraints\Email(mode="strict")
     * @\Doctrine\ORM\Mapping\Column(type="string", unique=true)
     * @\Symfony\Component\Serializer\Annotation\Groups({"import", "export-user", "export-claim"})
     */
    protected string $email = "";
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\FqcnAnnotationToAttribute\Fixture;

#[\Doctrine\ORM\Mapping\Entity]
#[\Doctrine\ORM\Mapping\Index(name: 'name_index', columns: ['name'])]
#[\Doctrine\ORM\Mapping\Index(name: 'surname_index', columns: ['surname'])]
#[\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity('azureB2cUuid')]
#[\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity('uuid')]
#[\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity('email')]
#[\Doctrine\ORM\Mapping\Table('user')]
class SingleQuote
{
    #[\Symfony\Component\Validator\Constraints\NotBlank]
    #[\Symfony\Component\Validator\Constraints\Email(mode: 'strict')]
    #[\Doctrine\ORM\Mapping\Column(type: 'string', unique: true)]
    #[\Symfony\Component\Serializer\Annotation\Groups(['import', 'export-user', 'export-claim'])]
    protected string $email = "";
}

?>
