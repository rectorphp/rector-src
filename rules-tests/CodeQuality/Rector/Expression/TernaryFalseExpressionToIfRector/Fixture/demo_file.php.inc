<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\Fixture;

final class DemoFile
{
    public function run( $foo, $bar )
    {
    ?>
        <input type="number" name="<?php echo $foo; ?>" value="<?php isset( $bar ) ? $bar : 0 ?>">
    <?php 
    }
}

?>
-----

