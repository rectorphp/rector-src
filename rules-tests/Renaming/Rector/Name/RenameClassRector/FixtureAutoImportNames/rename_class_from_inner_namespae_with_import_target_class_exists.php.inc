<?php

namespace Some2;

class RenameClassFromInnerNamespaceWithImportTargetClassExists
{
    /**
     * @return Storage
     */
    public function bar(){}
}

?>
-----
<?php

namespace Some2;

use Foo2\Storage;

class RenameClassFromInnerNamespaceWithImportTargetClassExists
{
    /**
     * @return Storage
     */
    public function bar(){}
}

?>
