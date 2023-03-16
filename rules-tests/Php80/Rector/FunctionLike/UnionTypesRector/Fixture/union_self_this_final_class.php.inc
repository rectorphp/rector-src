<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

final class UnionSelfThisFinalClass
{
    /**
     * @return $this
     */
    public function withFromId(int $fromId): self
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

final class UnionSelfThisFinalClass
{
    public function withFromId(int $fromId): self
    {
        return $this;
    }
}

?>