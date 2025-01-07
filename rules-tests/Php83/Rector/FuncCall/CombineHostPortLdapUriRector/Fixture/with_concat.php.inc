<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class WithConcat
{
    public function run()
    {
        $authConnection = ldap_connect($this->protocol . $this->server, $this->port);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class WithConcat
{
    public function run()
    {
        $authConnection = ldap_connect($this->protocol . $this->server . ':' . $this->port);
    }
}

?>
