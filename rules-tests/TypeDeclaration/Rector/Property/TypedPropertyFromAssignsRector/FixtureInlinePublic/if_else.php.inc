<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureInlinePublic;

final class IfElse
{
    protected $stringOrInteger = 'hi';

    public function setNumber()
    {
        if (mt_rand(0, 100)) {
            $this->stringOrInteger = 'hey';
        } else {
            $this->stringOrInteger = 1000;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureInlinePublic;

final class IfElse
{
    /**
     * @var int|string
     */
    protected $stringOrInteger = 'hi';

    public function setNumber()
    {
        if (mt_rand(0, 100)) {
            $this->stringOrInteger = 'hey';
        } else {
            $this->stringOrInteger = 1000;
        }
    }
}

?>
