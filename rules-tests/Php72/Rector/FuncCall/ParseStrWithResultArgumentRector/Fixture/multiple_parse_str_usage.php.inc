<?php

namespace Rector\Tests\Php72\Rector\FuncCall\ParseStrWithResultArgumentRector\Fixture;

class MultipleParseStrUsage
{
    public function run()
    {
        $query = 'bla';
        parse_str($query);
        $data = get_defined_vars();

        $query2 = 'bla2';
        parse_str($query2);
        $data2 = get_defined_vars();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\FuncCall\ParseStrWithResultArgumentRector\Fixture;

class MultipleParseStrUsage
{
    public function run()
    {
        $query = 'bla';
        parse_str($query, $result);
        $data = $result;

        $query2 = 'bla2';
        parse_str($query2, $result);
        $data2 = $result;
    }
}

?>
