<?php

class NoNamespace
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
-----
<?php

class NoNamespace
{
    public function run()
    {
        return new Anonymous__1d04bdd061e5548df3200e0a8bd62e19__0();
    }
}
class Anonymous__1d04bdd061e5548df3200e0a8bd62e19__0
{
    public function execute()
    {
    }
}
