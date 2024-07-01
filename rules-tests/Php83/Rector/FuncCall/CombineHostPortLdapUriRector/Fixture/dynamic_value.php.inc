<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class DynamicValue
{
    public function run($host, $port)
    {
        $ldapconn = ldap_connect($host, $port);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class DynamicValue
{
    public function run($host, $port)
    {
        $ldapconn = ldap_connect("{$host}:{$port}");
    }
}

?>
