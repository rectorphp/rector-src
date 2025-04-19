<?php

class SkipIfNoNamespace
{
    /**
     * @param \DateTime $dateTime
     */
    public function runA($dateTime)
    {
    }

    /**
     * @param \DateTimeInterface $dateTime
     */
    public function runB(\DateTimeInterface $dateTime)
    {
    }
}

?>
-----
<?php

class SkipIfNoNamespace
{
    /**
     * @param DateTime $dateTime
     */
    public function runA($dateTime)
    {
    }

    /**
     * @param DateTimeInterface $dateTime
     */
    public function runB(DateTimeInterface $dateTime)
    {
    }
}

?>
