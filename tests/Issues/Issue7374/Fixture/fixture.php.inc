<?php

namespace Rector\Core\Tests\Issues\7374\Fixture;

class R extends Reports {
    
    private function displayTree($name, $data, $other = null) {
        if (empty($data)) return false;
    }
    
    public function action($params, $server) {

        $bb = new BusibooksBusinesses();
              
        $this->displayTree("Equity", $equity, array(
            array("name" => "NET INCOME", "amount" => $net_income),
            array("name" => "RETAINED EARNINGS", "amount" => $retained_earnings)
        ));
    }

}

?>