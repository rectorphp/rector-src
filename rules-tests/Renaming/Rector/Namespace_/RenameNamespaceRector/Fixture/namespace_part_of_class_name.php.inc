<?php

namespace OldNamespace;

class OldNamespace
{
    private bool $isInitialized = false;
    
    public function __construct()
    {
        $this->isInitialized = true;
    }
}

namespace MyDifferent\OtherNamespace;

use OldNamespace\OldNamespace;

class MyClassFactory
{
    public function createInstanceFromFullQualifiedClassName(): \OldNamespace\OldNamespace
    {
        return new \OldNamespace\OldNamespace();
    }

    public function createInstanceFromImportedName(): OldNamespace
    {
        return new OldNamespace();
    }
}


?>
-----
<?php

namespace NewNamespace;

class OldNamespace
{
    private bool $isInitialized = false;
    
    public function __construct()
    {
        $this->isInitialized = true;
    }
}

namespace MyDifferent\OtherNamespace;

use NewNamespace\OldNamespace;

class MyClassFactory
{
    public function createInstanceFromFullQualifiedClassName(): \NewNamespace\OldNamespace
    {
        return new \NewNamespace\OldNamespace();
    }

    public function createInstanceFromImportedName(): \NewNamespace\OldNamespace
    {
        return new \NewNamespace\OldNamespace();
    }
}


?>
