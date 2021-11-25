<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

class Board
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BoardMoodImage")
     */
    private $moodImages;

    public function getMoodImages()
    {
        return $this->moodImages;
    }

    public function setMoodImages($moodImages)
    {
        $this->moodImages = $moodImages;
    }

    public function addMoodImage(BoardMoodImage $moodImage)
    {
        $this->moodImages[] = $moodImage;

        return $this;
    }

    public function removeMoodImage(BoardMoodImage $moodImage)
    {
        $this->moodImages->removeElement($moodImage);
    }
}
?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

class Board
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BoardMoodImage")
     */
    private ?\Doctrine\Common\Collections\Collection $moodImages = null;

    public function getMoodImages()
    {
        return $this->moodImages;
    }

    public function setMoodImages($moodImages)
    {
        $this->moodImages = $moodImages;
    }

    public function addMoodImage(BoardMoodImage $moodImage)
    {
        $this->moodImages[] = $moodImage;

        return $this;
    }

    public function removeMoodImage(BoardMoodImage $moodImage)
    {
        $this->moodImages->removeElement($moodImage);
    }
}
?>
