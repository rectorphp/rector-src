<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class DemoFile
{
    private $content_type;
    
    public function run()
    {
        $this->content_type = $content_type = "Something";
        
        return $content_type;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $content_type = "Something";
        
        return $content_type;
    }
}

?>
