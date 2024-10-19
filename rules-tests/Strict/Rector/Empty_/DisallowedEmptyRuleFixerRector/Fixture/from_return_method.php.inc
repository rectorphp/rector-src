<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class FromReturnCall
{
    public function run()
    {
        return empty($this->getProperty());
    }

    public function run2()
    {
        return ! empty($this->getProperty());
    }

    public function getProperty(): string
    {
        return '';
    }
}

?>
-----
<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class FromReturnCall
{
    public function run()
    {
        return in_array($this->getProperty(), ['', '0'], true);
    }

    public function run2()
    {
        return !in_array($this->getProperty(), ['', '0'], true);
    }

    public function getProperty(): string
    {
        return '';
    }
}

?>
