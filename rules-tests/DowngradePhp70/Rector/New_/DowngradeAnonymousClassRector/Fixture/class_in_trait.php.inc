<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

trait ClassInTrait
{
    public function run()
    {
        $message = 'error';

        $first = new class($message) extends \InvalidArgumentException {};

        $second = new class() extends \Exception {};
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

trait ClassInTrait
{
    public function run()
    {
        $message = 'error';

        $first = new Anonymous__3e394521acb7827bb92d281feff1e6ff__0($message);

        $second = new Anonymous__3e394521acb7827bb92d281feff1e6ff__1();
    }
}
class Anonymous__3e394521acb7827bb92d281feff1e6ff__0 extends \InvalidArgumentException
{
}
class Anonymous__3e394521acb7827bb92d281feff1e6ff__1 extends \Exception
{
}
