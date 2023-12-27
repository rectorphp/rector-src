<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class Fixture
{
    public function run()
    {
        $ldapconn = ldap_connect('ldap://ldap.example.com', 389);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class Fixture
{
    public function run()
    {
        $ldapconn = ldap_connect('ldap://ldap.example.com:389');
    }
}

?>