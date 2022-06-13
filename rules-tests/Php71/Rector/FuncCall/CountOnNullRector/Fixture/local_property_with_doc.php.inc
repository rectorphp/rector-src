<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

final class LocalPropertyWithDoc
{
    /**
     * @var mixed
     */
    private $notTitles;

    public function getTitle()
    {
        $this->titles = null;
        $titleCount = count($this->titles);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

final class LocalPropertyWithDoc
{
    /**
     * @var mixed
     */
    private $notTitles;

    public function getTitle()
    {
        $this->titles = null;
        $titleCount = is_array($this->titles) || $this->titles instanceof \Countable ? count($this->titles) : (is_null($this->titles) ? 0 : 1);
    }
}

?>
