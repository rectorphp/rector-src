<?php

class InNoNamespaceShortName
{
    public function run(\DateTime $dateTime)
    {
    }
}

?>
-----
<?php

class InNoNamespaceShortName
{
    public function run(DateTime $dateTime)
    {
    }
}

?>
