<?php

class DoctrineDeepAnnotation
{
    /**
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Comment", cascade={"persist","remove"})
     * @Doctrine\ORM\Mapping\JoinTable(name="absence_comment", joinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="request_id", referencedColumnName="id")}, inverseJoinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="comment_id", referencedColumnName="id")})
     * @Doctrine\ORM\Mapping\OrderBy({"createdAt" = "DESC"})
     */
    public $property;
}

?>
-----
<?php

use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;

class DoctrineDeepAnnotation
{
    /**
     * @ManyToMany(targetEntity="Comment", cascade={"persist", "remove"})
     * @Doctrine\ORM\Mapping\JoinTable(name="absence_comment", joinColumns={@JoinColumn(name="request_id", referencedColumnName="id")}, inverseJoinColumns={@JoinColumn(name="comment_id", referencedColumnName="id")})
     * @Doctrine\ORM\Mapping\OrderBy({"createdAt" = "DESC"})
     */
    public $property;
}
