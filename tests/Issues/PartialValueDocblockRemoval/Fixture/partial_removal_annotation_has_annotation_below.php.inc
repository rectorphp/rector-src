<?php

namespace Rector\Core\Tests\Issues\PartialValueDocblockRemoval\Fixture;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups as JmsGroups;

/**
 * @ORM\Entity()
 */
class PartialRemovalAnnotationHasAnnotationBelow
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     *
     * @Expose
     * @JmsGroups({"list", "details"})
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\PartialValueDocblockRemoval\Fixture;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups as JmsGroups;

/**
 * @ORM\Entity()
 */
class PartialRemovalAnnotationHasAnnotationBelow
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Expose
     * @JmsGroups({"list", "details"})
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

?>
