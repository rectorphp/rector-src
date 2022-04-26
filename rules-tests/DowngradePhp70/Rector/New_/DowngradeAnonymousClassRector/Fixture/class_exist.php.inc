<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

class AnonymousFor_ClassExists
{
}
class ClassExists
{
    public function run()
    {
        return new class {
            public function execute()
            {
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\New_\DowngradeAnonymousClassRector\Fixture;

class AnonymousFor_ClassExists
{
}
class ClassExists
{
    public function run()
    {
        return new Anonymous__8012caaa6db2848028c58729537f4951__0();
    }
}
class Anonymous__8012caaa6db2848028c58729537f4951__0
{
    public function execute()
    {
    }
}
