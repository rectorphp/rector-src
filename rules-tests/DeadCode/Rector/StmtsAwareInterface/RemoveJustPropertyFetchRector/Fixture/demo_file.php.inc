<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector\Fixture;

final class DemoFile
{
    public function run( $arg )
    {
        $name = $arg->name; ?>
        <input type="text" value="<?php echo $name; ?>">
    <?php }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector\Fixture;

final class DemoFile
{
    public function run( $arg )
    { ?>
        <input type="text" value="<?php echo $arg->name; ?>">
    <?php }
}

?>
