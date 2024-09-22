<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

final class DemoFile
{
    public function run(string $param)
    {        
        $r = preg_replace( '#^/(foo|bar)/(\w+)/.+$#', '$2', $param, 1, $count );
        if ( $count !== 1 ) {
            $r = preg_replace( '#^/hello/(\w+)/.+$#', '$1', $param, 1 );
        }

        return $r;
    }
}
?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

final class DemoFile
{
    public function run(string $param)
    {        
        $r = preg_replace( '#^/(foo|bar)/(\w+)/.+$#', '$2', $param, 1, $count );
        if ( $count === 1 ) {
            return $r;
        }

        return preg_replace( '#^/hello/(\w+)/.+$#', '$1', $param, 1 );
    }
}

?>
