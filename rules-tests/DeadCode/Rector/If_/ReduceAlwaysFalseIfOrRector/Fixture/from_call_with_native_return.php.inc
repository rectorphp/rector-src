<?php

namespace Rector\Tests\DeadCode\Rector\If_\ReduceAlwaysFalseIfOrRector\Fixture;

class FromCallWithNativeReturn
{
    /**
     * @return \DateTime
     */
    private function get(): \DateTime
    {
        return new \DateTime('now');
    }

    public function run($number)
    {
        if (! $this->get() || $number > 50) {
            return 'yes';
        }

        return 'no';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\ReduceAlwaysFalseIfOrRector\Fixture;

class FromCallWithNativeReturn
{
    /**
     * @return \DateTime
     */
    private function get(): \DateTime
    {
        return new \DateTime('now');
    }

    public function run($number)
    {
        if ($number > 50) {
            return 'yes';
        }

        return 'no';
    }
}

?>