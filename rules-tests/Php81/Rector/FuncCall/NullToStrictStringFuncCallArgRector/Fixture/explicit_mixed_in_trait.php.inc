<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

trait ExplicitMixedInTrait
{
    public function setTitle(mixed $title)
    {
        $this->title = $title;
        return trim($this->title);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

trait ExplicitMixedInTrait
{
    public function setTitle(mixed $title)
    {
        $this->title = $title;
        return trim((string) $this->title);
    }
}

?>
