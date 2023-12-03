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
        return $this->getProperty() === '' || $this->getProperty() === '0';
    }

    public function run2()
    {
        return $this->getProperty() !== '' && $this->getProperty() !== '0';
    }

    public function getProperty(): string
    {
        return '';
    }
}

?>
