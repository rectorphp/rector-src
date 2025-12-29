<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Node\ClassMethod;

interface UpdateDocName
{
    /**
     * @param ClassMethod|Node\Stmt\Property $value
     */
    public function method(Node\Stmt $value);
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Node\ClassMethod;

interface UpdateDocName
{
    /**
     * @param ClassMethod|Node\Stmt\Property $stmt
     */
    public function method(Node\Stmt $stmt);
}

?>
