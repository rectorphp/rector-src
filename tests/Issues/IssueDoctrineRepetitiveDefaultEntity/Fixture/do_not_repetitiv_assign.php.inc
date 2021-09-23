<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueDoctrineRepetitiveDefaultEntity\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="Domain\Repository\ArticleRepository")
 */
class DoNotrepetitiveAssign
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected ?\DateTimeInterface $createdAt;

    /**
     * @var Collection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="Category", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="articles_categories")
     */
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueDoctrineRepetitiveDefaultEntity\Fixture;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="Domain\Repository\ArticleRepository")
 */
class DoNotrepetitiveAssign
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $createdAt;

    /**
     * @var Collection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="Category", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="articles_categories")
     */
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

}

?>