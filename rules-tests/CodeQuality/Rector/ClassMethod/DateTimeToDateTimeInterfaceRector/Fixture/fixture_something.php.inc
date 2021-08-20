<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector\Fixture;

final class FixtureSomething
{
    private \DateTime $date;

    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }
}

?>
    -----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector\Fixture;

final class FixtureSomething
{
    /**
     * @var \DateTime|\DateTimeImmutable
     */
    private \DateTimeInterface $date;

    /**
     * @param \DateTime|\DateTimeImmutable $date
     */
    public function __construct(\DateTimeInterface $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime|\DateTimeImmutable $date
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }
}
