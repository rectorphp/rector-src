<?php

namespace Rector\Core\Tests\Issues\Issue7374\Fixture;

final class ClassWithTwoMethods
{
    private function displayTree($name, $data, $other = null)
    {
        if (empty($data)) return false;
    }

    public function action($params, $server)
    {
        $bb = new BusibooksBusinesses();

        $this->displayTree("Equity", $equity, array(
            array("name" => "NET INCOME", "amount" => $net_income),
            array("name" => "RETAINED EARNINGS", "amount" => $retained_earnings)
        ));
    }
}
-----
<?php

namespace Rector\Core\Tests\Issues\Issue7374\Fixture;

final class ClassWithTwoMethods
{
    private function displayTree($data)
    {
        if (empty($data)) return false;
    }

    public function action($params, $server)
    {
        $busibooksBusinesses = new BusibooksBusinesses();

        $this->displayTree($equity);
    }
}
