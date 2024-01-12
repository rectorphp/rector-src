<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

class ParamByReferenceInTargetMethod
{
    public function run()
    {
        $arr = ['string'];
        array_walk_recursive($arr, [$this, 'append']);
        var_dump($arr);
    }

    private function append (string &$str) {
        $str .= " append this";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

class ParamByReferenceInTargetMethod
{
    public function run()
    {
        $arr = ['string'];
        array_walk_recursive($arr, fn(string &$str) => $this->append($str));
        var_dump($arr);
    }

    private function append (string &$str) {
        $str .= " append this";
    }
}

?>
