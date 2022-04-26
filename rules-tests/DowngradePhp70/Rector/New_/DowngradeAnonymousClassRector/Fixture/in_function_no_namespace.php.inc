<?php

function inFunctionNoNamespace()
{
    return new class {
            public function execute()
            {
            }
    };
}
-----
<?php

function inFunctionNoNamespace()
{
    return new Anonymous__60cd16e8cd388ee3c469e74b249ed0e3__0();
}
class Anonymous__60cd16e8cd388ee3c469e74b249ed0e3__0
{
    public function execute()
    {
    }
}
