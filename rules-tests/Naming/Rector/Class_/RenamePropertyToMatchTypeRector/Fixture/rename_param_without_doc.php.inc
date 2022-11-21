<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\Core\NodeAnalyzer\ClassAnalyzer;

final class RenameParamWithoutDoc
{
    private ClassAnalyzer $analyzer;
    
    public function __construct(
        ClassAnalyzer $analyzer
    ) {
        $this->analyzer = $analyzer;
    }

    public function getAnalyzer(): ClassAnalyzer
    {
        return $this->analyzer;
    }
}
?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\Core\NodeAnalyzer\ClassAnalyzer;

final class RenameParamWithoutDoc
{
    private ClassAnalyzer $classAnalyzer;
    
    public function __construct(
        ClassAnalyzer $classAnalyzer
    ) {
        $this->classAnalyzer = $classAnalyzer;
    }

    public function getAnalyzer(): ClassAnalyzer
    {
        return $this->classAnalyzer;
    }
}

?>
